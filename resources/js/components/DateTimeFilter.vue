<template>
  <div class="flex flex-col gap-2">
    <div class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">DateTime Range</div>
    <div class="flex flex-col md:flex-row gap-2">
      <div class="flex-1">
        <label class="text-xs text-gray-600 dark:text-gray-400">From</label>
        <input
          type="datetime-local"
          :value="filterStore.dateTimeFrom"
          @input="handleDateTimeFromChange"
          class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
        />
      </div>
      <div class="flex-1">
        <label class="text-xs text-gray-600 dark:text-gray-400">To</label>
        <input
          type="datetime-local"
          :value="filterStore.dateTimeTo"
          @input="handleDateTimeToChange"
          class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
        />
      </div>
      <div class="flex items-end">
        <button
          v-if="filterStore.hasDateTimeFilter"
          @click="clearFilters"
          class="px-2 py-1 text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"
          title="Clear datetime filters"
        >
          Clear
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useFiltersStore } from '../stores/filters.js';
import { useLogViewerStore } from '../stores/logViewer.js';

const filterStore = useFiltersStore();
const logViewerStore = useLogViewerStore();

const handleDateTimeFromChange = (event) => {
  filterStore.setDateTimeFrom(event.target.value);
  logViewerStore.loadLogs();
};

const handleDateTimeToChange = (event) => {
  filterStore.setDateTimeTo(event.target.value);
  logViewerStore.loadLogs();
};

const clearFilters = () => {
  filterStore.clearDateTimeFilters();
  logViewerStore.loadLogs();
};
</script>
