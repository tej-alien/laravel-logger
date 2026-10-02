<template>
  <div v-if="showFilters" class="filter-panel">
    <Menu as="div" class="relative inline-block text-left">
      <MenuButton class="menu-button flex items-center gap-1">
        <FunnelIcon class="w-5 h-5" />
        <span class="hidden md:inline">Filters</span>
        <span v-if="filterStore.hasAnyFilter" class="ml-1 px-1.5 py-0.5 text-xs bg-blue-600 text-white rounded-full">
          {{ activeFilterCount }}
        </span>
      </MenuButton>

      <transition
        enter-active-class="transition ease-out duration-100"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
      >
        <MenuItems class="absolute right-0 z-30 mt-2 w-96 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
          <div class="p-4 max-h-[80vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Filter Logs</h3>
              <button
                v-if="filterStore.hasAnyFilter"
                @click="clearAllFilters"
                class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"
              >
                Clear All
              </button>
            </div>

            <div class="space-y-4 divide-y divide-gray-200 dark:divide-gray-700">
              <div>
                <DateTimeFilter />
              </div>

              <div class="pt-4">
                <RequestMethodFilter />
              </div>

              <div class="pt-4">
                <StatusCodeFilter />
              </div>
            </div>
          </div>
        </MenuItems>
      </transition>
    </Menu>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Menu, MenuButton, MenuItems } from '@headlessui/vue';
import { FunnelIcon } from '@heroicons/vue/24/solid';
import { useFiltersStore } from '../stores/filters.js';
import { useLogViewerStore } from '../stores/logViewer.js';
import { useFileStore } from '../stores/files.js';
import { useSearchStore } from '../stores/search.js';
import DateTimeFilter from './DateTimeFilter.vue';
import RequestMethodFilter from './RequestMethodFilter.vue';
import StatusCodeFilter from './StatusCodeFilter.vue';

const filterStore = useFiltersStore();
const logViewerStore = useLogViewerStore();
const fileStore = useFileStore();
const searchStore = useSearchStore();

const showFilters = computed(() => {
  return fileStore.selectedFile || String(searchStore.query || '').trim().length > 0;
});

const activeFilterCount = computed(() => {
  let count = 0;
  if (filterStore.hasDateTimeFilter) count++;
  if (filterStore.hasMethodFilter) count++;
  if (filterStore.hasStatusCodeFilter) count++;
  return count;
});

const clearAllFilters = () => {
  filterStore.clearAllFilters();
  logViewerStore.loadLogs();
};
</script>
