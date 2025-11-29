<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

const props = defineProps<{
    roles: Array<{ id: number; name: string }>;
}>();

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [] as number[], // ← role IDs
});

const loading = ref(false);

function submit() {
    loading.value = true;

    router.post('/users', form, {
        onFinish: () => (loading.value = false),
    });
}
</script>

<template>
    <Head title="Create User" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Users', href: '/users' },
            { title: 'Create', href: '/users' }
        ]"
    >
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold">Create User</h1>

            <Link href="/users">
                <button
                    class="rounded-full border-b-4 border-blue-700 bg-[#2A7CC7] mb-4 px-4 py-1 text-white hover:border-blue-500 hover:bg-[#1F5E98]"
                >
                    ← Back
                </button>
            </Link>

            <form @submit.prevent="submit" class="space-y-4">
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
                    <label>Password</label>
                    <input
                        type="password"
                        v-model="form.password"
                        class="w-full rounded border p-2"
                    />
                    <InputError :message="$page.props.errors?.password" />
                </div>

                <div>
                    <label>Confirm Password</label>
                    <input
                        type="password"
                        v-model="form.password_confirmation"
                        class="w-full rounded border p-2"
                    />
                </div>

                <div>
                    <label>Roles</label>

                    <div class="space-y-2">
                        <label
                            v-for="role in props.roles"
                            :key="role.id"
                            class="flex items-center space-x-2"
                        >
                            <input
                                type="checkbox"
                                :value="role.id"
                                v-model="form.roles"
                            />
                            <span>{{ role.name }}</span>
                        </label>
                    </div>

                    <InputError :message="$page.props.errors?.roles" />
                </div>

                <button
                    class="rounded-full border-b-4 border-[#285F39] bg-[#2F7542] px-3 py-2 text-white hover:border-[#2F7542] hover:bg-[#245C34]"
                    :disabled="loading"
                >
                    <span v-if="!loading">Create User</span>
                    <span v-else>Processing...</span>
                </button>
            </form>
        </div>
    </AppLayout>
</template>
