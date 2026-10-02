<template>
  <div class="flex flex-col gap-2">
    <div class="flex items-center justify-between">
      <div class="text-xs font-medium text-gray-700 dark:text-gray-300">HTTP Status Codes</div>
      <div class="flex gap-1">
        <button
          @click="filterStore.selectAllStatusCodes(); logViewerStore.loadLogs()"
          class="text-xs px-2 py-0.5 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300"
        >
          All
        </button>
        <button
          @click="filterStore.deselectAllStatusCodes(); logViewerStore.loadLogs()"
          class="text-xs px-2 py-0.5 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300"
        >
          None
        </button>
      </div>
    </div>
    <div class="flex flex-col gap-3">
      <div
        v-for="group in filterStore.statusCodeGroups"
        :key="group.label"
        class="flex flex-col gap-1"
      >
        <button
          @click="toggleGroup(group.codes)"
          class="text-left text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
        >
          {{ group.label }}
        </button>
        <div class="flex flex-wrap gap-1">
          <button
            v-for="code in group.codes"
            :key="code"
            @click="toggleStatusCode(code)"
            :class="[
              'px-2 py-1 text-xs font-mono rounded transition-colors',
              isStatusCodeSelected(code)
                ? getStatusCodeClass(code)
                : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-300 dark:hover:bg-gray-600'
            ]"
          >
            {{ code }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useFiltersStore } from '../stores/filters.js';
import { useLogViewerStore } from '../stores/logViewer.js';

const filterStore = useFiltersStore();
const logViewerStore = useLogViewerStore();

const isStatusCodeSelected = (code) => {
  return !filterStore.excludedStatusCodes.includes(code);
};

const toggleStatusCode = (code) => {
  filterStore.toggleStatusCode(code);
  logViewerStore.loadLogs();
};

const toggleGroup = (codes) => {
  filterStore.toggleStatusCodeGroup(codes);
  logViewerStore.loadLogs();
};

const getStatusCodeClass = (code) => {
  if (code >= 200 && code < 300) {
    return 'bg-green-600 text-white hover:bg-green-700';
  } else if (code >= 300 && code < 400) {
    return 'bg-blue-600 text-white hover:bg-blue-700';
  } else if (code >= 400 && code < 500) {
    return 'bg-yellow-600 text-white hover:bg-yellow-700';
  } else if (code >= 500) {
    return 'bg-red-600 text-white hover:bg-red-700';
  }
  return 'bg-gray-600 text-white hover:bg-gray-700';
};
</script>
