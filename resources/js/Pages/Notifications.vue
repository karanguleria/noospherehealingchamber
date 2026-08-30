<template>
  <div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
      <div>
        <h1 class="font-normal text-xl md:text-xl flex items-center" dusk="notifications-heading">
          <span>Notifications</span>
          <span
            v-if="localUnread > 0"
            class="ml-3 inline-flex items-center justify-center rounded-full bg-primary-500 text-white text-xs font-bold px-2 py-0.5 min-w-[1.5rem]"
          >
            {{ localUnread > 99 ? '99+' : localUnread }}
          </span>
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          All of your account notifications in one place.
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-2">
        <button
          type="button"
          class="border appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center shadow h-9 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50"
          :disabled="processing || localUnread === 0"
          @click="markAllRead"
        >
          Mark all as read
        </button>
        <button
          type="button"
          class="border appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center shadow h-9 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50"
          :disabled="processing || items.length === 0"
          @click="deleteAll"
        >
          Delete all
        </button>
      </div>
    </div>

    <Card class="overflow-hidden">
      <div v-if="items.length === 0" class="py-16 text-center text-gray-500 dark:text-gray-400">
        There are no notifications.
      </div>

      <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
        <div
          v-for="notification in items"
          :key="notification.id"
          class="relative flex flex-col sm:flex-row sm:items-start gap-4 p-5 transition-colors"
          :class="notification.read_at ? 'bg-transparent' : 'bg-primary-500/5 dark:bg-primary-500/10'"
        >
          <span
            v-if="!notification.read_at"
            class="absolute top-5 right-5 rounded-full bg-primary-500 w-2 h-2"
          />

          <div
            class="shrink-0 mt-0.5"
            :class="notification.iconClass || 'text-sky-500'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0A24.255 24.255 0 0112 18.75c-2.676 0-5.216-.584-7.499-1.632m14.998 0A3.001 3.001 0 0112 21a3.001 3.001 0 01-5.714-1.918" />
            </svg>
          </div>

          <div class="flex-1 min-w-0 pr-4">
            <p class="text-gray-700 dark:text-gray-200 leading-normal break-words">
              {{ notification.message }}
            </p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" :title="notification.created_at">
              {{ notification.created_at_friendly }}
            </p>

            <div class="mt-4 flex flex-wrap items-center gap-3">
              <button
                v-if="actionHref(notification)"
                type="button"
                class="border appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center shadow h-8 px-3 bg-primary-500 border-primary-500 text-white dark:text-gray-900 hover:bg-primary-400"
                @click="openAction(notification)"
              >
                {{ notification.actionText || 'View' }}
              </button>

              <button
                type="button"
                class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                @click="toggleRead(notification)"
              >
                {{ notification.read_at ? 'Mark Unread' : 'Mark Read' }}
              </button>

              <button
                type="button"
                class="text-sm text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400"
                @click="remove(notification)"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="notifications.meta && notifications.meta.last_page > 1"
        class="flex items-center justify-between gap-3 px-5 py-4 border-t border-gray-200 dark:border-gray-700"
      >
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Showing {{ notifications.meta.from }}–{{ notifications.meta.to }} of {{ notifications.meta.total }}
        </p>
        <div class="flex gap-2">
          <a
            v-if="notifications.meta.current_page > 1"
            :href="pageUrl(notifications.meta.current_page - 1)"
            class="border rounded text-sm font-bold h-8 px-3 inline-flex items-center bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
            @click.prevent="goToPage(notifications.meta.current_page - 1)"
          >
            Previous
          </a>
          <a
            v-if="notifications.meta.current_page < notifications.meta.last_page"
            :href="pageUrl(notifications.meta.current_page + 1)"
            class="border rounded text-sm font-bold h-8 px-3 inline-flex items-center bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
            @click.prevent="goToPage(notifications.meta.current_page + 1)"
          >
            Next
          </a>
        </div>
      </div>
    </Card>
  </div>
</template>

<script>
export default {
  props: {
    notifications: {
      type: Object,
      default: () => ({ data: [], meta: {}, links: [] }),
    },
    unreadCount: {
      type: Number,
      default: 0,
    },
  },

  data() {
    return {
      processing: false,
      items: [],
      localUnread: 0,
    }
  },

  watch: {
    notifications: {
      immediate: true,
      deep: true,
      handler(value) {
        this.items = value?.data ? [...value.data] : []
      },
    },
    unreadCount: {
      immediate: true,
      handler(value) {
        this.localUnread = value || 0
      },
    },
  },

  methods: {
    pageUrl(page) {
      return `/nova/notifications?page=${page}`
    },

    goToPage(page) {
      if (window.Nova) {
        window.Nova.visit(`/notifications?page=${page}`)
      } else {
        window.location.href = this.pageUrl(page)
      }
    },

    actionHref(notification) {
      const url = notification.actionUrl
      if (!url) return null
      if (typeof url === 'string') return url
      return url.url || null
    },

    openAction(notification) {
      if (!notification.read_at) {
        this.toggleRead(notification, false)
      }

      if (window.Nova && notification.actionUrl) {
        window.Nova.visit(notification.actionUrl, {
          openInNewTab: notification.openInNewTab || false,
        })
        return
      }

      const href = this.actionHref(notification)
      if (href) {
        window.location.href = href
      }
    },

    async toggleRead(notification, emitRefresh = true) {
      const wasUnread = !notification.read_at
      const endpoint = wasUnread
        ? `/nova/notifications/${notification.id}/read`
        : `/nova/notifications/${notification.id}/unread`

      try {
        await window.Nova.request().post(endpoint)
        notification.read_at = wasUnread ? new Date().toISOString() : null
        this.localUnread = Math.max(0, this.localUnread + (wasUnread ? -1 : 1))
        if (emitRefresh) {
          window.Nova.$emit('refresh-notifications')
        }
      } catch (e) {
        console.error(e)
      }
    },

    async remove(notification) {
      if (!confirm('Are you sure you want to delete this notification?')) {
        return
      }

      try {
        await window.Nova.request().delete(`/nova/notifications/${notification.id}`)
        if (!notification.read_at) {
          this.localUnread = Math.max(0, this.localUnread - 1)
        }
        this.items = this.items.filter((item) => item.id !== notification.id)
        window.Nova.$emit('refresh-notifications')
      } catch (e) {
        console.error(e)
      }
    },

    async markAllRead() {
      this.processing = true
      try {
        await window.Nova.request().post('/nova/notifications/read-all')
        this.items = this.items.map((item) => ({
          ...item,
          read_at: item.read_at || new Date().toISOString(),
        }))
        this.localUnread = 0
        window.Nova.$emit('refresh-notifications')
      } catch (e) {
        console.error(e)
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
        await window.Nova.request().delete('/nova/notifications/all')
        this.items = []
        this.localUnread = 0
        window.Nova.$emit('refresh-notifications')
        window.Nova.visit('/notifications')
      } catch (e) {
        console.error(e)
      } finally {
        this.processing = false
      }
    },
  },
}
</script>
