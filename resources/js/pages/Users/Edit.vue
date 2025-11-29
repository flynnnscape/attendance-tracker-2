<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

const props = defineProps<{
    user: {
        id: number;
        name: string;
        email: string;
        roles: Array<{ id: number; name: string }>;
    };
    roles: Array<{ id: number; name: string }>;
}>();

const form = reactive({
    name: props.user.name,
    email: props.user.email,
    role: props.user.roles[0]?.id ?? '',

});

const loading = ref(false);

function submit() {
    loading.value = true;

    router.put(`/users/${props.user.id}`, form, {
        onFinish: () => (loading.value = false),
    });
}
</script>

<template>
    <Head title="Edit User" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Users', href: '/users' },
            { title: 'Edit', href: '/users' }
        ]"
    >
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold">Edit User</h1>

            <Link href="/users">
                <button class="rounded-full border-b-4 border-blue-700 bg-[#2A7CC7] px-3 py-1 text-white hover:border-blue-500 hover:bg-[#1F5E98]" >
                    ← Back
                </button>
            </Link>

            <form @submit.prevent="submit" class="mt-7 space-y-4">
                <div>
                    <label>Name</label>
                    <input v-model="form.name" class="w-full rounded border p-2" />
                    <InputError :message="$page.props.errors?.name" />
                </div>

                <div>
                    <label>Email</label>
                    <input v-model="form.email" class="w-full rounded border p-2" />
                    <InputError :message="$page.props.errors?.email" />
                </div>

                <div>
                    <label>Role</label>
                    <select v-model="form.role" class="w-full rounded border p-2">
                        <option
                            v-for="role in props.roles"
                            :key="role.id"
                            :value="role.id"
                        >
                            {{ role.name }}
                        </option>
                    </select>
                </div>



                <button
                    class="rounded-full border-b-4 border-[#285F39] bg-[#2F7542] px-3 py-2 text-white hover:border-[#2F7542] hover:bg-[#245C34]" 
                    :disabled="loading"
                >
                    <span v-if="!loading">Update User</span>
                    <span v-else>Processing...</span>
                </button>
            </form>
        </div>
    </AppLayout>
</template>
