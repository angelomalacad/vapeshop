<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';

import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { dashboard as adminDashboard } from '@/routes/admin';
import { dashboard as branchAdminDashboard } from '@/routes/branch-admin';
import { dashboard as customerDashboard } from '@/routes/customer';
import { dashboard as driverDashboard } from '@/routes/driver';

import { type NavItem } from '@/types';

import AppLogo from './AppLogo.vue';

const page = usePage();

const user = page.props.auth.user as {
    role?: string;
};

const dashboardUrl = (() => {
    switch (user.role) {
    case 'super_admin':
    case 'admin':
        return adminDashboard().url;

    case 'branch_admin':
        return branchAdminDashboard().url;

    case 'driver':
        return driverDashboard().url;

    case 'customer':
    default:
        return customerDashboard().url;
}
})();

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboardUrl,
        icon: LayoutGrid,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboardUrl">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>