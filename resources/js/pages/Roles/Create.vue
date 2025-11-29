<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    permissions: Array,
});

const form = ref({
    name: '',
    permissions: [], // MUST BE ARRAY
});

const loading = ref(false);

function submit() {
    loading.value = true;

    router.post('/roles', form.value, {
        onFinish: () => (loading.value = false),
    });
}
</script>

<template>
    <Head title="Create Role" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Role', href: '/roles' },
            { title: 'Create', href: '/roles' },
        ]"
    >
        <div class="mx-auto max-w-xl p-6">
            <h1 class="mb-4 text-2xl font-bold text-[#1B3A28]">Create Role</h1>

            <Link href="/roles" class="mb-4 inline-block">
                <button
                    class="rounded-full border-b-4 border-blue-700 bg-[#2A7CC7] px-3 py-1 text-white hover:border-blue-500 hover:bg-[#1F5E98]"
                >
                    <- Back
                </button>
            </Link>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block font-medium">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full rounded border border-gray-400 px-2 py-2"
                    />
                    <InputError :message="$page.props.errors?.name" />
                </div>

                <div>
                    <label class="block font-medium">Permissions</label>

                    <label
                        v-for="permission in permissions" 
                        class="flex items-center space-x-2"
                    >
                        <input
                            type="checkbox"
                            class="form-checkbox h-5 w-5"
                            :value="permission"
                            v-model="form.permissions"
                        />
                        <span>{{ permission }}</span>
                    </label>

                    <InputError :message="$page.props.errors?.permissions" />
                </div>

                <button
                    type="submit"
                    class="rounded-full border-b-4 border-[#285F39] bg-[#2F7542] px-3 py-2 text-white hover:border-[#2F7542] hover:bg-[#245C34]"
                    :disabled="loading"
                >
                    <span v-if="!loading">Create Role</span>
                    <span v-else>Processing...</span>
                </button>
            </form>
        </div>
    </AppLayout>
</template>
