import { usePage} from '@inertiajs/vue3';

export function can(permission: string): Boolean{
    const page = usePage();

    const permissions = (page.props.auth.permissions as string[]) ?? [];


    return permissions.includes(permission);
}
