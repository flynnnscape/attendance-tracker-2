<script setup lang="ts">
import { LinkComponentBaseProps, Method } from '@inertiajs/core';
import { Link } from '@inertiajs/vue3';

interface Props {
    href: LinkComponentBaseProps['href'];
    tabindex?: number;
    method?: Method;
    as?: string;
}

defineProps<Props>();
</script>

<template>
    <Link
        :href="href"
        :tabindex="tabindex"
        :method="method"
        :as="as"
        class="text-[#245C34] hover:underline hover:decoration-[#245C34] underline-offset-1 transition-colors duration-300 ease-in dark:hover:decoration-neutral-500"
    >
        <slot />
    </Link>
</template>
