<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import type { Component } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import * as LucideIcons from 'lucide-vue-next';

const page = usePage();
const user = page.props.auth.user;
const menu = page.props.menu as NavItem[];
const currentUrl = page.url;

const expandedMenus = ref<string[]>([]);
const icons = LucideIcons as unknown as Record<string, Component>;

const hasPermission = (permission: string): boolean => {
    if (!permission) return true;
    return user?.permissions?.includes(permission);
};

const filterItems = (items: NavItem[]): NavItem[] => {
    return items
        .filter((item) => !item.permission || hasPermission(item.permission))
        .map((item) => ({
            ...item,
            children: item.children ? filterItems(item.children) : undefined,
        }));
};

const filteredMenu = computed(() => filterItems(menu));

const toggleMenu = (title: string) => {
    if (expandedMenus.value.includes(title)) {
        expandedMenus.value = expandedMenus.value.filter(t => t !== title);
    } else {
        expandedMenus.value.push(title);
    }
};

const isExpanded = (title: string) => expandedMenus.value.includes(title);

// Expand parent menus if current URL matches a child
onMounted(() => {
    filteredMenu.value.forEach(item => {
        if (item.children?.some(child => currentUrl.startsWith(child.href))) {
            expandedMenus.value.push(item.title);
        }
    });
});
</script>


<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu class="overflow-x-hidden">
            <template v-for="item in filteredMenu" :key="item.title">
                <SidebarMenuItem>
                    <!-- Parent item with children -->
                    <SidebarMenuButton
                        v-if="item.children?.length"
                        @click="toggleMenu(item.title)"
                        class="cursor-pointer"
                        :tooltip="item.title"
                    >
                        <component :is="icons[item.icon]" v-if="item.icon" />
                        <span>{{ item.title }}</span>
                        <span class="ml-auto text-sm">{{ isExpanded(item.title) ? '▾' : '▸' }}</span>
                    </SidebarMenuButton>

                    <!-- Parent item without children -->
                    <SidebarMenuButton
                        v-else
                        as-child
                        :is-active="item.href === currentUrl"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="icons[item.icon]" v-if="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>

                    <!-- Child items -->
                    <SidebarMenu
                        v-if="item.children && isExpanded(item.title)"
                        class="ml-4"
                    >
                        <SidebarMenuItem
                            v-for="child in item.children"
                            :key="child.title"
                        >
                            <SidebarMenuButton
                                as-child
                                :is-active="child.href === currentUrl"
                                :tooltip="child.title"
                            >
                                <Link :href="child.href">
                                    <component :is="icons[child.icon]" v-if="child.icon" class="mr-1" />
                                    <span>{{ child.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>

