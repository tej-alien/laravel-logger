<?php

namespace App\Concerns\LogReader;

use App\Utils\Utils;

trait CanFilterUsingIndex
{
    protected ?string $query = null;
    protected ?int $onlyShowIndex = null;
    protected ?array $excludedMethods = null;
    protected ?array $excludedStatusCodes = null;

    /**
     * Load only the provided log levels
     *
     * @alias setLevels
     *
     * @param  string|array|null  $levels
     */
    public function only($levels = null): static
    {
        return $this->setLevels($levels);
    }

    /**
     * Load only the provided log levels
     *
     * @param  string|array|null  $levels
     */
    public function setLevels($levels = null): static
    {
        $this->index()->forLevels($levels);

        return $this;
    }

    public function allLevels(): static
    {
        return $this->setLevels(null);
    }

    /**
     * Load all log levels except the provided ones.
     *
     * @alias exceptLevels
     *
     * @param  string|array|null  $levels
     */
    public function except($levels = null): static
    {
        return $this->exceptLevels($levels);
    }

    /**
     * Load all log levels except the provided ones.
     *
     * @param  string|array|null  $levels
     */
    public function exceptLevels($levels = null): static
    {
        $this->index()->exceptLevels($levels);

        return $this;
    }

    public function skip(int $number): static
    {
        $this->index()->skip($number);

        return $this;
    }

    public function limit(int $number): static
    {
        $this->index()->limit($number);

        return $this;
    }

    public function search(?string $query = null): static
    {
        return $this->setQuery($query);
    }

    protected function setQuery(?string $query = null): static
    {
        $this->closeFile();

        if (! empty($query) && str_starts_with($query, 'log-index:')) {
            $this->query = null;
            $this->only(null);
            $this->onlyShowIndex = intval(explode(':', $query)[1]);
        } elseif (! empty($query)) {
            $query = '~'.$query.'~iu';

            Utils::validateRegex($query);

            $this->query = $query;
        } else {
            $this->query = null;
        }

        return $this;
    }

    /**
     * Exclude specific request methods from results.
     *
     * @param array|null $methods
     */
    public function exceptMethods(?array $methods = null): static
    {
        $this->excludedMethods = $methods;

        return $this;
    }

    /**
     * Exclude specific status codes from results.
     *
     * @param array|null $statusCodes
     */
    public function exceptStatusCodes(?array $statusCodes = null): static
    {
        $this->excludedStatusCodes = $statusCodes;

        return $this;
    }

    /**
     * Apply datetime range filter to the index.
     *
     * @param \Carbon\CarbonInterface|string|null $from
     * @param \Carbon\CarbonInterface|string|null $to
     */
    public function forDateRange($from = null, $to = null): static
    {
        $this->index()->forDateRange($from, $to);

        return $this;
    }

    /**
     * Check if a log should be filtered out based on method and status code filters.
     */
    protected function shouldFilterLog($log): bool
    {
        // Filter by request method
        if (!empty($this->excludedMethods) && isset($log->context['method'])) {
            if (in_array($log->context['method'], $this->excludedMethods)) {
                return true;
            }
        }

        // Filter by status code
        if (!empty($this->excludedStatusCodes) && isset($log->context['status_code'])) {
            if (in_array($log->context['status_code'], $this->excludedStatusCodes)) {
                return true;
            }
        }

        return false;
    }
}
