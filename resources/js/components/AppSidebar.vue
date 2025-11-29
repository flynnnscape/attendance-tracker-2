<script setup lang="ts">
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
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, UsersRound, UserRound, Notebook, House, UserRoundCheck, Building2,
         CalendarCheck, Wallet, CalendarDays, FileText,   
       } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage();
const roles = (page.props.auth?.roles as string[]) ?? [];
const role = roles[0] ?? 'admin';

const universalMenu: NavItem[] = [
{ title: 'Dashboard', href: dashboard(), icon: House },
];

const roleMenus: Record<string, NavItem[]> = {
Admin: [
{ title: 'Users Management', href: '/users', icon: UsersRound},
{ title: 'Roles Management', href: '/roles', icon: Notebook},
{ title: 'Department Management', href: '/departments', icon: Building2},
{ title: 'Attendance Management', href: '/attendance', icon: CalendarCheck},
{ title: 'PayRoll Management', href: '/payroll', icon: Wallet},
{ title: 'Schedules', href: '/schedules', icon: CalendarDays},
{ title: 'Reports', href: '/reports', icon: FileText},
],
Head: [
{ title: 'My Team', href: '/employees', icon: UsersRound },
{ title: 'Team Attendance', href: '/attendance', icon: Notebook },
{ title: 'Approvals', href: '/attendance', icon: Notebook },
{ title: 'Reports', href: '/attendance', icon: Notebook },
{ title: 'PayRoll', href: '/payroll', icon: Notebook },
],
HR: [
{ title: 'Employees', href: '/departments', icon: Folder },
{ title: 'Attendance', href: '/departments', icon: Folder },
{ title: 'Leaves & Overtime', href: '/departments', icon: Folder },
{ title: 'Payroll', href: '/departments', icon: Folder },
{ title: 'Reports', href: '/departments', icon: Folder },
{ title: 'Settings', href: '/departments', icon: Folder },
],
Users: [
{ title: 'My Attendance', href: '/profile', icon: UserRound },
{ title: 'Requests', href: '/users', icon: UsersRound },
{ title: 'Schedule', href: '/users', icon: UsersRound },
{ title: 'Payroll', href: '/users', icon: UsersRound },
],
};

const mainNavItems = [
...universalMenu,
...(roleMenus[role] ?? []),
];
// const footerNavItems: NavItem[] = [
//     {
//         title: 'Github Repo',
//         href: 'https://github.com/laravel/vue-starter-kit',
//         icon: Folder,
//     },
//     {
//         title: 'Documentation',
//         href: 'https://laravel.com/docs/starter-kits#vue',
//         icon: BookOpen,
//     },
// ];
</script>

<template>
    <Sidebar collapsible="icon" variant="floating">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
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
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
