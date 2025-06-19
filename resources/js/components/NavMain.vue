<script setup lang="ts">
import { ref, computed } from 'vue';
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
const expandedMenus = ref<string[]>([]);
const icons = LucideIcons as unknown as Record<string, Component>;
const toggleMenu = (title: string) => {
    if (expandedMenus.value.includes(title)) {
        expandedMenus.value = expandedMenus.value.filter(t => t !== title);
    } else {
        expandedMenus.value.push(title);
    }
};

const isExpanded = (title: string) => expandedMenus.value.includes(title);

const hasPermission = (permission: string): boolean => {
    if (!permission) return true;
    return user?.permissions?.some((p: any) => p === permission);
};

const filterItems = (items: NavItem[]): NavItem[] => {
    return items
        .filter((item) => {
            // Only check for permission, remove role check
            return item.permission ? hasPermission(item.permission) : true;
        })
        .map((item) => ({
            ...item,
            children: item.children ? filterItems(item.children) : undefined,
        }));
};
const filteredMenu = computed(() => filterItems(menu));
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu class="overflow-x-hidden">
            <template v-for="item in filteredMenu" :key="item.title">
                <SidebarMenuItem>
                    <SidebarMenuButton
                        v-if="item.children && item.children.length > 0"
                        @click="toggleMenu(item.title)"
                        class="cursor-pointer"
                        :tooltip="item.title"
                    >
                        <component :is="icons[item.icon]" v-if="item.icon" />
                        <span>{{ item.title }}</span>
                        <span class="ml-auto text-sm">{{ isExpanded(item.title) ? '▾' : '▸' }}</span>
                    </SidebarMenuButton>

                    <SidebarMenuButton
                        v-else
                        as-child
                        :is-active="item.href === page.url"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="icons[item.icon]" v-if="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>

                    <!-- Child Menu -->
                    <SidebarMenu
                        v-if="item.children && isExpanded(item.title)"
                        class="ml-4 transition-all duration-200"
                    >
                        <SidebarMenuItem
                            v-for="child in item.children"
                            :key="child.title"
                        >
                            <SidebarMenuButton
                                as-child
                                :is-active="child.href === page.url"
                            >
                                <Link :href="child.href">
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
