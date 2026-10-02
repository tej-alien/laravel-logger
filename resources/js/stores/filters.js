import { defineStore } from 'pinia';
import { useLocalStorage } from '@vueuse/core';

const shouldUseLocalStorage = window.LogViewer?.defaults?.use_local_storage ?? true;

export const useFiltersStore = defineStore({
  id: 'filters',

  state: () => ({
    // DateTime filters
    dateTimeFrom: shouldUseLocalStorage
      ? useLocalStorage('logViewerDateTimeFrom', null)
      : null,
    dateTimeTo: shouldUseLocalStorage
      ? useLocalStorage('logViewerDateTimeTo', null)
      : null,

    // Request method filters
    availableMethods: ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
    excludedMethods: shouldUseLocalStorage
      ? useLocalStorage('logViewerExcludedMethods', [])
      : [],

    // Status code filters
    excludedStatusCodes: shouldUseLocalStorage
      ? useLocalStorage('logViewerExcludedStatusCodes', [])
      : [],
    statusCodeGroups: [
      { label: '1xx Informational', codes: [100, 101, 102, 103] },
      { label: '2xx Success', codes: [200, 201, 202, 203, 204, 205, 206] },
      { label: '3xx Redirection', codes: [300, 301, 302, 303, 304, 307, 308] },
      { label: '4xx Client Error', codes: [400, 401, 403, 404, 405, 406, 408, 409, 410, 413, 414, 415, 422, 429] },
      { label: '5xx Server Error', codes: [500, 501, 502, 503, 504, 505] },
    ],
  }),

  getters: {
    hasDateTimeFilter: (state) => {
      return state.dateTimeFrom !== null || state.dateTimeTo !== null;
    },

    hasMethodFilter: (state) => {
      return state.excludedMethods.length > 0;
    },

    hasStatusCodeFilter: (state) => {
      return state.excludedStatusCodes.length > 0;
    },

    hasAnyFilter() {
      return this.hasDateTimeFilter || this.hasMethodFilter || this.hasStatusCodeFilter;
    },

    selectedMethods: (state) => {
      return state.availableMethods.filter(method => !state.excludedMethods.includes(method));
    },
  },

  actions: {
    setDateTimeFrom(datetime) {
      this.dateTimeFrom = datetime;
    },

    setDateTimeTo(datetime) {
      this.dateTimeTo = datetime;
    },

    clearDateTimeFilters() {
      this.dateTimeFrom = null;
      this.dateTimeTo = null;
    },

    toggleMethod(method) {
      if (this.excludedMethods.includes(method)) {
        this.excludedMethods = this.excludedMethods.filter(m => m !== method);
      } else {
        this.excludedMethods.push(method);
      }
    },

    selectAllMethods() {
      this.excludedMethods = [];
    },

    deselectAllMethods() {
      this.excludedMethods = [...this.availableMethods];
    },

    toggleStatusCode(statusCode) {
      if (this.excludedStatusCodes.includes(statusCode)) {
        this.excludedStatusCodes = this.excludedStatusCodes.filter(code => code !== statusCode);
      } else {
        this.excludedStatusCodes.push(statusCode);
      }
    },

    toggleStatusCodeGroup(codes) {
      const allExcluded = codes.every(code => this.excludedStatusCodes.includes(code));

      if (allExcluded) {
        // Include all codes in the group
        this.excludedStatusCodes = this.excludedStatusCodes.filter(code => !codes.includes(code));
      } else {
        // Exclude all codes in the group
        codes.forEach(code => {
          if (!this.excludedStatusCodes.includes(code)) {
            this.excludedStatusCodes.push(code);
          }
        });
      }
    },

    selectAllStatusCodes() {
      this.excludedStatusCodes = [];
    },

    deselectAllStatusCodes() {
      const allCodes = this.statusCodeGroups.flatMap(group => group.codes);
      this.excludedStatusCodes = [...allCodes];
    },

    clearAllFilters() {
      this.clearDateTimeFilters();
      this.selectAllMethods();
      this.selectAllStatusCodes();
    },
  },
})
