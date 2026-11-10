<template>
  <div class="nhc-notifications-page">
    <!-- Header -->
    <div class="nhc-hero">
      <div class="nhc-hero-copy">
        <p class="nhc-eyebrow">Inbox</p>
        <h1 class="nhc-title">Notifications</h1>
        <p class="nhc-subtitle">
          Stay on top of sessions, invitations, and account activity.
        </p>
      </div>

      <div class="nhc-actions">
        <button
          type="button"
          class="nhc-btn nhc-btn-ghost"
          :disabled="!localUnread || processing"
          @click="markAllRead"
        >
          Mark all read
        </button>
        <button
          type="button"
          class="nhc-btn nhc-btn-danger"
          :disabled="!localTotal || processing"
          @click="deleteAll"
        >
          Delete all
        </button>
      </div>
    </div>

    <!-- Stats -->
    <div class="nhc-stats">
      <div class="nhc-stat">
        <span class="nhc-stat-label">Total</span>
        <span class="nhc-stat-value">{{ localTotal }}</span>
      </div>
      <div class="nhc-stat nhc-stat-accent">
        <span class="nhc-stat-label">Unread</span>
        <span class="nhc-stat-value">{{ localUnread }}</span>
      </div>
      <div class="nhc-stat">
        <span class="nhc-stat-label">Read</span>
        <span class="nhc-stat-value">{{ localRead }}</span>
      </div>
    </div>

    <!-- Filters + per page -->
    <div class="nhc-toolbar">
      <div class="nhc-chips">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          type="button"
          class="nhc-chip"
          :class="{ 'nhc-chip-active': currentFilter === tab.value }"
          @click="changeFilter(tab.value)"
        >
          {{ tab.label }}
          <span class="nhc-chip-count">{{ tab.count }}</span>
        </button>
      </div>

      <label class="nhc-per-page">
        <span>Show</span>
        <select
          :value="currentPerPage"
          :disabled="processing"
          @change="changePerPage($event.target.value)"
        >
          <option v-for="size in pageSizes" :key="size" :value="size">{{ size }}</option>
        </select>
      </label>
    </div>

    <!-- Empty -->
    <div v-if="localNotifications.length === 0" class="nhc-empty">
      <div class="nhc-empty-icon" v-html="iconFor('bell')" />
      <p class="nhc-empty-title">No notifications here</p>
      <p class="nhc-empty-text">{{ emptyMessage }}</p>
    </div>

    <!-- List -->
    <div v-else class="nhc-list">
      <article
        v-for="item in localNotifications"
        :key="item.id"
        class="nhc-card"
        :class="{ 'nhc-card-unread': !item.read_at }"
      >
        <div
          class="nhc-icon"
          :class="typeClass(item.type)"
          v-html="iconFor(item.icon)"
        />

        <div class="nhc-card-body">
          <div class="nhc-card-top">
            <div class="min-w-0">
              <p class="nhc-card-title">
                {{ splitMessage(item.message).title }}
              </p>
              <p
                v-if="splitMessage(item.message).subtitle"
                class="nhc-card-subtitle"
              >
                {{ splitMessage(item.message).subtitle }}
              </p>
            </div>
            <span
              v-if="!item.read_at"
              class="nhc-dot"
              title="Unread"
            />
          </div>

          <p class="nhc-card-time" :title="item.created_at">
            {{ item.created_at_friendly }}
          </p>

          <div class="nhc-card-actions">
            <button
              v-if="resolveActionUrl(item)"
              type="button"
              class="nhc-btn nhc-btn-primary"
              :disabled="processing"
              @click="openItem(item)"
            >
              {{ item.actionText || 'View' }}
            </button>
            <button
              type="button"
              class="nhc-btn nhc-btn-ghost"
              :disabled="processing"
              @click="toggleRead(item)"
            >
              {{ item.read_at ? 'Mark unread' : 'Mark read' }}
            </button>
            <button
              type="button"
              class="nhc-btn nhc-btn-ghost"
              :disabled="processing"
              @click="removeItem(item)"
            >
              Delete
            </button>
          </div>
        </div>
      </article>
    </div>

    <!-- Pagination -->
    <div v-if="pagination.last_page > 1 || pagination.total > 0" class="nhc-pagination">
      <p class="nhc-pagination-meta">
        <template v-if="pagination.total > 0">
          Showing
          <strong>{{ pagination.from }}</strong>
          -
          <strong>{{ pagination.to }}</strong>
          of
          <strong>{{ pagination.total }}</strong>
        </template>
        <template v-else>
          No results
        </template>
      </p>

      <div class="nhc-pagination-controls">
        <button
          type="button"
          class="nhc-page-btn"
          :disabled="!pagination.prev_page_url || processing"
          @click="goToPage(pagination.current_page - 1)"
        >
          Previous
        </button>

        <button
          v-for="page in visiblePages"
          :key="`page-${page}`"
          type="button"
          class="nhc-page-btn"
          :class="{
            'nhc-page-btn-active': page === pagination.current_page,
            'nhc-page-btn-ellipsis': page === '...',
          }"
          :disabled="page === '...' || processing"
          @click="page !== '...' && goToPage(page)"
        >
          {{ page }}
        </button>

        <button
          type="button"
          class="nhc-page-btn"
          :disabled="!pagination.next_page_url || processing"
          @click="goToPage(pagination.current_page + 1)"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    notifications: {
      type: Object,
      default: () => ({
        data: [],
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0,
        from: null,
        to: null,
        prev_page_url: null,
        next_page_url: null,
      }),
    },
    filter: {
      type: String,
      default: 'all',
    },
    perPage: {
      type: Number,
      default: 15,
    },
    unreadCount: {
      type: Number,
      default: 0,
    },
    readCount: {
      type: Number,
      default: 0,
    },
    totalCount: {
      type: Number,
      default: 0,
    },
  },

  data() {
    return {
      processing: false,
      localNotifications: [...(this.notifications?.data || [])],
      localUnread: this.unreadCount,
      localRead: this.readCount,
      localTotal: this.totalCount,
      currentFilter: this.filter,
      currentPerPage: this.perPage,
      pageSizes: [10, 15, 25, 50],
    }
  },

  computed: {
    tabs() {
      return [
        { label: 'All', value: 'all', count: this.localTotal },
        { label: 'Unread', value: 'unread', count: this.localUnread },
        { label: 'Read', value: 'read', count: this.localRead },
      ]
    },

    pagination() {
      return {
        current_page: this.notifications?.current_page || 1,
        last_page: this.notifications?.last_page || 1,
        per_page: this.notifications?.per_page || this.currentPerPage,
        total: this.notifications?.total || 0,
        from: this.notifications?.from || 0,
        to: this.notifications?.to || 0,
        prev_page_url: this.notifications?.prev_page_url || null,
        next_page_url: this.notifications?.next_page_url || null,
      }
    },

    visiblePages() {
      const current = this.pagination.current_page
      const last = this.pagination.last_page
      if (last <= 7) {
        return Array.from({ length: last }, (_, i) => i + 1)
      }

      const pages = new Set([1, last, current, current - 1, current + 1])
      if (current <= 3) {
        pages.add(2)
        pages.add(3)
        pages.add(4)
      }
      if (current >= last - 2) {
        pages.add(last - 1)
        pages.add(last - 2)
        pages.add(last - 3)
      }

      const sorted = [...pages].filter((p) => p >= 1 && p <= last).sort((a, b) => a - b)
      const result = []
      let prev = 0
      for (const page of sorted) {
        if (prev && page - prev > 1) {
          result.push('...')
        }
        result.push(page)
        prev = page
      }
      return result
    },

    emptyMessage() {
      if (this.currentFilter === 'unread') {
        return 'You are all caught up — no unread notifications.'
      }
      if (this.currentFilter === 'read') {
        return 'No read notifications on this page yet.'
      }
      return 'New session and invitation activity will show up here.'
    },
  },

  watch: {
    notifications: {
      deep: true,
      handler(value) {
        this.localNotifications = [...(value?.data || [])]
      },
    },
    unreadCount(value) {
      this.localUnread = value
    },
    readCount(value) {
      this.localRead = value
    },
    totalCount(value) {
      this.localTotal = value
    },
    filter(value) {
      this.currentFilter = value
    },
    perPage(value) {
      this.currentPerPage = value
    },
  },

  methods: {
    buildUrl({ page = 1, filter = this.currentFilter, perPage = this.currentPerPage } = {}) {
      const params = new URLSearchParams()
      params.set('page', String(page))
      params.set('filter', filter)
      params.set('per_page', String(perPage))
      return `/notifications?${params.toString()}`
    },

    visit(url) {
      if (window.Nova && typeof Nova.visit === 'function') {
        Nova.visit(url)
      } else {
        window.location.href = `/nova${url.startsWith('/') ? url : `/${url}`}`
      }
    },

    changeFilter(filter) {
      if (filter === this.currentFilter || this.processing) {
        return
      }
      this.visit(this.buildUrl({ page: 1, filter }))
    },

    changePerPage(perPage) {
      const size = Number(perPage)
      if (size === this.currentPerPage || this.processing) {
        return
      }
      this.visit(this.buildUrl({ page: 1, perPage: size }))
    },

    goToPage(page) {
      if (
        this.processing ||
        page < 1 ||
        page > this.pagination.last_page ||
        page === this.pagination.current_page
      ) {
        return
      }
      this.visit(this.buildUrl({ page }))
    },

    splitMessage(message) {
      const raw = (message || '').trim()
      const [first, ...rest] = raw.split('\n')
      return {
        title: first || '',
        subtitle: rest.join('\n').trim(),
      }
    },

    typeClass(type) {
      switch (type) {
        case 'success':
          return 'is-success'
        case 'error':
          return 'is-error'
        case 'warning':
          return 'is-warning'
        default:
          return 'is-info'
      }
    },

    iconFor(name) {
      const icons = {
        user: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>`,
        'user-add': `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>`,
        play: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" /></svg>`,
        save: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.75A2.25 2.25 0 0 0 4.5 6v12a2.25 2.25 0 0 0 2.25 2.25h10.5A2.25 2.25 0 0 0 19.5 18V9.75L14.25 4.5H12M9 3.75V9h6.75" /></svg>`,
        'check-circle': `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>`,
        mail: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>`,
        bell: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>`,
      }
      return icons[name] || icons.bell
    },

    async openItem(item) {
      if (!item.read_at) {
        await this.toggleRead(item, true)
      }
      const url = this.resolveActionUrl(item)
      if (url && window.Nova) {
        Nova.visit(url, { openInNewTab: item.openInNewTab || false })
      }
    },

    resolveActionUrl(item) {
      const raw = item?.actionUrl
      if (!raw) {
        return null
      }
      if (typeof raw === 'string') {
        return raw
      }
      if (typeof raw === 'object' && raw.url) {
        return raw.url
      }
      return null
    },

    async toggleRead(item, forceRead = false) {
      this.processing = true
      try {
        const wasUnread = !item.read_at
        const shouldRead = forceRead ? true : wasUnread

        // Already in the target state (e.g. openItem force-read on an unread-only path)
        if (shouldRead && !wasUnread) {
          return
        }
        if (!shouldRead && wasUnread) {
          return
        }

        const endpoint = shouldRead
          ? `/nova/notifications/${item.id}/read`
          : `/nova/notifications/${item.id}/unread`

        await Nova.request().post(endpoint)

        item.read_at = shouldRead ? new Date().toISOString() : null

        if (shouldRead) {
          this.localUnread = Math.max(0, this.localUnread - 1)
          this.localRead += 1
        } else {
          this.localRead = Math.max(0, this.localRead - 1)
          this.localUnread += 1
        }

        // Drop from list when it no longer matches the active filter
        if (
          (this.currentFilter === 'unread' && shouldRead) ||
          (this.currentFilter === 'read' && !shouldRead)
        ) {
          this.localNotifications = this.localNotifications.filter((n) => n.id !== item.id)
        }

        Nova.$emit('refresh-notifications')
      } finally {
        this.processing = false
      }
    },

    async removeItem(item) {
      if (!confirm('Are you sure you want to delete this notification?')) {
        return
      }

      this.processing = true
      try {
        await Nova.request().delete(`/nova/notifications/${item.id}`)
        this.localNotifications = this.localNotifications.filter((n) => n.id !== item.id)

        this.localTotal = Math.max(0, this.localTotal - 1)
        if (!item.read_at) {
          this.localUnread = Math.max(0, this.localUnread - 1)
        } else {
          this.localRead = Math.max(0, this.localRead - 1)
        }

        Nova.$emit('refresh-notifications')

        if (this.localNotifications.length === 0 && this.pagination.current_page > 1) {
          this.goToPage(this.pagination.current_page - 1)
        } else if (this.localNotifications.length === 0) {
          this.visit(this.buildUrl({ page: 1 }))
        }
      } finally {
        this.processing = false
      }
    },

    async markAllRead() {
      this.processing = true
      try {
        await Nova.request().post('/nova/notifications/read-all')
        const now = new Date().toISOString()
        this.localNotifications = this.localNotifications.map((n) => ({
          ...n,
          read_at: n.read_at || now,
        }))
        this.localRead = this.localTotal
        this.localUnread = 0

        if (this.currentFilter === 'unread') {
          this.localNotifications = []
        }

        Nova.$emit('refresh-notifications')
      } finally {
        this.processing = false
      }
    },

    async deleteAll() {
      if (!confirm('Are you sure you want to delete all the notifications?')) {
        return
      }
      this.processing = true
      try {
        await Nova.request().delete('/nova/notifications/all')
        this.localNotifications = []
        this.localUnread = 0
        this.localRead = 0
        this.localTotal = 0
        Nova.$emit('refresh-notifications')
        this.visit(this.buildUrl({ page: 1 }))
      } finally {
        this.processing = false
      }
    },
  },
}
</script>

<style>
.nhc-notifications-page {
  max-width: 920px;
}

.nhc-hero {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1.25rem;
}

@media (min-width: 640px) {
  .nhc-hero {
    flex-direction: row;
    align-items: flex-end;
    justify-content: space-between;
  }
}

.nhc-eyebrow {
  margin: 0 0 0.25rem;
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: rgb(100, 116, 139);
}

.nhc-title {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 600;
  line-height: 1.25;
  color: inherit;
}

.nhc-subtitle {
  margin: 0.35rem 0 0;
  font-size: 0.875rem;
  color: rgb(100, 116, 139);
}

.dark .nhc-subtitle,
.dark .nhc-eyebrow {
  color: rgb(148, 163, 184);
}

.nhc-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.nhc-stats {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 0.75rem;
  margin-bottom: 1.1rem;
}

.nhc-stat {
  padding: 0.9rem 1rem;
  border-radius: 1rem;
  border: 1px solid rgba(148, 163, 184, 0.28);
  background: rgba(255, 255, 255, 0.7);
}

.dark .nhc-stat {
  background: rgba(31, 41, 55, 0.85);
  border-color: rgba(75, 85, 99, 0.7);
}

.nhc-stat-accent {
  border-color: rgba(var(--colors-primary-500), 0.45);
  background: rgba(var(--colors-primary-500), 0.08);
}

.nhc-stat-label {
  display: block;
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: rgb(100, 116, 139);
}

.nhc-stat-value {
  display: block;
  margin-top: 0.25rem;
  font-size: 1.35rem;
  font-weight: 700;
  line-height: 1.2;
}

.nhc-toolbar {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

@media (min-width: 640px) {
  .nhc-toolbar {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
}

.nhc-chips {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.nhc-chip {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  height: 2.1rem;
  padding: 0 0.85rem;
  border-radius: 999px;
  border: 1px solid rgba(148, 163, 184, 0.35);
  font-size: 0.75rem;
  font-weight: 600;
  background: transparent;
  cursor: pointer;
}

.nhc-chip-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 1.25rem;
  height: 1.25rem;
  padding: 0 0.35rem;
  border-radius: 999px;
  font-size: 0.65rem;
  background: rgba(148, 163, 184, 0.18);
}

.nhc-chip-active {
  background: rgba(var(--colors-primary-500));
  border-color: rgba(var(--colors-primary-500));
  color: #fff;
}

.dark .nhc-chip-active {
  color: #111827;
}

.nhc-chip-active .nhc-chip-count {
  background: rgba(255, 255, 255, 0.28);
  color: inherit;
}

.nhc-per-page {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  font-size: 0.75rem;
  color: rgb(100, 116, 139);
}

.nhc-per-page select {
  height: 2.1rem;
  border-radius: 0.65rem;
  border: 1px solid rgba(148, 163, 184, 0.35);
  background: transparent;
  padding: 0 0.55rem;
  font-size: 0.75rem;
  font-weight: 600;
  color: inherit;
}

.nhc-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.nhc-card {
  display: flex;
  gap: 1rem;
  padding: 1rem 1.1rem;
  border-radius: 1rem;
  border: 1px solid rgba(148, 163, 184, 0.25);
  background: rgba(255, 255, 255, 0.78);
  box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
  transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.dark .nhc-card {
  background: rgba(31, 41, 55, 0.92);
  border-color: rgba(75, 85, 99, 0.7);
}

.nhc-card-unread {
  border-color: rgba(var(--colors-primary-500), 0.45);
  box-shadow: inset 3px 0 0 rgba(var(--colors-primary-500), 1);
}

.nhc-icon {
  width: 2.6rem;
  height: 2.6rem;
  border-radius: 0.9rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.nhc-icon.is-success {
  background: rgba(34, 197, 94, 0.12);
  color: rgb(34, 197, 94);
}

.nhc-icon.is-error {
  background: rgba(239, 68, 68, 0.12);
  color: rgb(239, 68, 68);
}

.nhc-icon.is-warning {
  background: rgba(234, 179, 8, 0.14);
  color: rgb(202, 138, 4);
}

.nhc-icon.is-info {
  background: rgba(24, 182, 155, 0.14);
  color: rgb(24, 182, 155);
}

.nhc-card-body {
  min-width: 0;
  flex: 1;
}

.nhc-card-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 0.75rem;
}

.nhc-card-title {
  margin: 0;
  font-size: 0.9rem;
  font-weight: 650;
  line-height: 1.35;
  word-break: break-word;
}

.nhc-card-subtitle {
  margin: 0.2rem 0 0;
  font-size: 0.75rem;
  color: rgb(100, 116, 139);
  word-break: break-all;
}

.dark .nhc-card-subtitle {
  color: rgb(148, 163, 184);
}

.nhc-dot {
  margin-top: 0.35rem;
  width: 0.5rem;
  height: 0.5rem;
  border-radius: 999px;
  background: rgba(var(--colors-primary-500));
  flex-shrink: 0;
}

.nhc-card-time {
  margin: 0.55rem 0 0;
  font-size: 0.68rem;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  color: rgb(148, 163, 184);
}

.nhc-card-actions {
  margin-top: 0.75rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.45rem;
}

.nhc-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 2rem;
  padding: 0 0.75rem;
  border-radius: 0.65rem;
  font-size: 0.75rem;
  font-weight: 600;
  border: 1px solid transparent;
  transition: 0.15s ease;
  cursor: pointer;
}

.nhc-btn:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

.nhc-btn-primary {
  background: rgba(var(--colors-primary-500));
  border-color: rgba(var(--colors-primary-500));
  color: #fff;
}

.dark .nhc-btn-primary {
  color: #111827;
}

.nhc-btn-ghost {
  background: transparent;
  border-color: rgba(148, 163, 184, 0.35);
  color: inherit;
}

.nhc-btn-danger {
  background: transparent;
  border-color: rgba(239, 68, 68, 0.45);
  color: rgb(239, 68, 68);
}

.nhc-empty {
  text-align: center;
  padding: 3.5rem 1rem;
  border-radius: 1rem;
  border: 1px dashed rgba(148, 163, 184, 0.4);
  background: rgba(148, 163, 184, 0.04);
}

.nhc-empty-icon {
  width: 3.5rem;
  height: 3.5rem;
  margin: 0 auto 0.85rem;
  border-radius: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(148, 163, 184, 0.12);
  color: rgb(148, 163, 184);
}

.nhc-empty-title {
  margin: 0;
  font-size: 0.9rem;
  font-weight: 650;
}

.nhc-empty-text {
  margin: 0.35rem 0 0;
  font-size: 0.75rem;
  color: rgb(100, 116, 139);
}

.nhc-pagination {
  margin-top: 1.25rem;
  padding-top: 1rem;
  border-top: 1px solid rgba(148, 163, 184, 0.25);
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

@media (min-width: 640px) {
  .nhc-pagination {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
}

.nhc-pagination-meta {
  margin: 0;
  font-size: 0.75rem;
  color: rgb(100, 116, 139);
}

.nhc-pagination-controls {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem;
}

.nhc-page-btn {
  min-width: 2.1rem;
  height: 2.1rem;
  padding: 0 0.65rem;
  border-radius: 0.65rem;
  border: 1px solid rgba(148, 163, 184, 0.35);
  background: transparent;
  font-size: 0.75rem;
  font-weight: 600;
  cursor: pointer;
}

.nhc-page-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.nhc-page-btn-active {
  background: rgba(var(--colors-primary-500));
  border-color: rgba(var(--colors-primary-500));
  color: #fff;
}

.dark .nhc-page-btn-active {
  color: #111827;
}

.nhc-page-btn-ellipsis {
  border-color: transparent;
  cursor: default;
}
</style>
