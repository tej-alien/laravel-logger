<template>
  <div class="flex flex-col gap-2">
    <div class="flex items-center justify-between">
      <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Request Methods</div>
      <div class="flex gap-1">
        <button
          @click="filterStore.selectAllMethods(); logViewerStore.loadLogs()"
          class="text-xs px-2 py-0.5 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300"
        >
          All
        </button>
        <button
          @click="filterStore.deselectAllMethods(); logViewerStore.loadLogs()"
          class="text-xs px-2 py-0.5 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300"
        >
          None
        </button>
      </div>
    </div>
    <div class="flex flex-wrap gap-2">
      <button
        v-for="method in filterStore.availableMethods"
        :key="method"
        @click="toggleMethod(method)"
        :class="[
          'px-3 py-1 text-xs font-medium rounded transition-colors',
          isMethodSelected(method)
            ? 'bg-blue-600 text-white hover:bg-blue-700'
            : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-300 dark:hover:bg-gray-600'
        ]"
      >
        {{ method }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { useFiltersStore } from '../stores/filters.js';
import { useLogViewerStore } from '../stores/logViewer.js';

const filterStore = useFiltersStore();
const logViewerStore = useLogViewerStore();

const isMethodSelected = (method) => {
  return !filterStore.excludedMethods.includes(method);
};

const toggleMethod = (method) => {
  filterStore.toggleMethod(method);
  logViewerStore.loadLogs();
};
</script>
