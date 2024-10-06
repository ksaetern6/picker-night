import { type Filter } from '@/Components/Filters/FilterSheet.vue'
import { defineStore } from 'pinia'

export const usePickerStore = defineStore('picker', {
    state: () => {
        return {
            filters: [] as Filter[]
        }
    },
    getters: {
        enabledFilters(): Filter[] {
            return this.filters.filter((f) => f.enabled)
        },
        disabledFilters(): Filter[] {
            return this.filters.filter((f) => !f.enabled)
        },
        enabledFiltersOptions(): string[] {
            if (this.enabledFilters.length) {
                return this.enabledFilters.flatMap((f) => f.options.flat())
            }
            return ['']
        }
    },
    actions: {
        switchFiltersById(id: string): void { // add loading
            let index = this.filters.findIndex((f) => f.id && f.id === id)
            const status = this.filters[index].enabled
            this.filters[index].enabled = !status

            this.patchFilter(this.filters[index], 'enabled')
        },
        async addFilter(filter: Filter): Promise<boolean> {
            const found = this.filters.filter((f) => f.name == filter.name)
            if (found.length === 0) {
                const data = await this.postFilter(filter)
                if (data?.id) {
                    filter.id = data.id
                    this.filters.push(filter)
                    return true
                }
            }
            return false
        },
        async updateFilterById(newFilter: Filter, id: string): Promise<void> {
            const index = this.filters.findIndex((f) => f?.id && f.id === id)
            
            const resp = await this.patchFilter(newFilter, 'options')

            this.filters[index] = newFilter
        },
        // refactor to own api file
        // add error checking
        async postFilter(filter: Filter): Promise<Filter | null> {
            const _token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!_token) {
                // error
                return null
            }

            const resp = await fetch('/filters', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': _token
                },
                body: JSON.stringify({
                    filter: filter
                })
            })
            
            return resp.json()
        },

        // add error checking
        async patchFilter(filter: Filter, field: string): Promise<Filter | null> {
            const _token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!_token) {
                // error
                return null
            }

            const resp = await fetch('/filters', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': _token
                },
                body: JSON.stringify({
                    [field]: filter[field as keyof Filter],
                    id: filter.id
                })
            })

            return resp.json()
        }
    }
})