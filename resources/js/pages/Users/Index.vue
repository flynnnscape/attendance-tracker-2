    <script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import { can } from '@/lib/can';
    import { type BreadcrumbItem } from '@/types';
    import { Head, Link, router } from '@inertiajs/vue3';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Users', href: '/users' },
    ];

    defineProps<{
        users: Array<{
            id: number;
            name: string;
            email: string;
            status: string;
            roles: string[];
        }>;
    }>();

    function deleteUser(id: number) {
        if (confirm('Delete this user?')) {
            router.delete(`/users/${id}`);
        }
    }
    </script>

    <template>
        <Head title="Users" />

        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="p-6">
                <h1 class="mb-4 text-2xl font-bold text-[#1B3A28]">Users</h1>

                <Link
                    v-if="can('users.create')"
                    href="/users/create"
                    class="rounded-full border-b-4 border-[#285F39] bg-[#2F7542] px-3 py-2 text-white hover:border-[#2F7542] hover:bg-[#245C34]" >
                    + Add User
                </Link>

                <table class="mt-4 w-full border">
                    <thead>
                        <tr class="border-b text-center">
                            <th class="p-2">ID</th>
                            <th class="p-2">Name</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Roles</th>
                            <th class="p-2">Status</th>
                            <th v-if="can('users.edit')" class=" p-2 ">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="user in users"
                            :key="user.id"
                            class="border-b text-center"
                        >
                            <td class="p-2">{{ user.id }}</td>
                            <td class="p-2">{{ user.name }}</td>
                            <td class="p-2">{{ user.email }}</td>
                            <td class="p-2">{{ user.roles }}</td>

                            <td>
                                <span
                                    :class="user.status === 'active'
                                        ? 'font-bold text-[#245C34]'
                                        : 'font-bold text-red-600'"
                                >
                                    {{ user.status === 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="text-center" v-if="can('users.edit')">
                                <Link :href="`/users/${user.id}/edit`">
                                    <button class="rounded-full border-b-4 border-blue-700 bg-[#2A7CC7] px-3 py-1 text-white hover:border-blue-500 hover:bg-[#1F5E98]"
                                >
                                        Edit
                                    </button>
                                </Link>
                                <span v-if="can('users.edit')" class="m-1">|</span>
                                <button
                                    v-if="can('users.delete')"
                                    @click="deleteUser(user.id)"
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
