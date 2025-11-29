<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { can } from '@/lib/can';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: '/roles',
    },
];

defineProps<{
    roles: Array<{
        id: number;
        name: string;
        permissions: Array<{ id: number; name: string }>;
    }>;
}>();

function deleteRole(id: number) {
    if (confirm('Delete this role?')) {
        router.delete(`/roles/${id}`);
    }
}
</script>

<template>
    <Head title="Roles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-4 text-2xl font-bold text-[#1B3A28]">Roles</h1>

            <Link
                
                href="/roles/create"
                class="rounded-full border-b-4 border-[#285F39] bg-[#2F7542] px-3 py-2 text-white hover:border-[#2F7542] hover:bg-[#245C34]"
            >
                + Add Role
            </Link>

            <table class="mt-4 w-full border">
                <thead>
                    <tr class="border-b ">
                        <th class="p-2 text-left">ID</th>
                        <th class="p-2 text-left">Name</th>
                        <th class="p-2 text-left">Permissions</th>
                        <th v-if="can('roles.edit')" class="p-2 text-center">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="role in roles" :key="role.id" class="border-b ">
                        <td class="p-2">{{ role.id }}</td>
                        <td class="p-2">{{ role.name }}</td>

                        <td class="p-2">
                            <span
                                v-for="permission in role.permissions"
                                :key="permission.id"
                                class="mr-2 inline-block rounded bg-gray-200 px-2 py-1 text-sm"
                            >
                                {{ permission.name }}
                            </span>
                        </td>

                        <td class="text-center">
                            <Link
                                 
                                :href="`/roles/${role.id}/edit`"
                            >
                                <button
                                    class="rounded-full border-b-4 border-blue-700 bg-[#2A7CC7] px-3 py-1 text-white hover:border-blue-500 hover:bg-[#1F5E98]"
                                >
                                    Edit
                                </button>
                            </Link>
                            <span   class="m-1">|</span>
                            <button
                                
                                @click="deleteRole(role.id)"
                                class="rounded-full border-b-4 border-red-700 bg-[#C24141] px-3 py-1 text-white hover:border-red-500 hover:bg-[#992F2F]"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
