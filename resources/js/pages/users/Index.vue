<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, User as UserIcon } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, User } from '@/types';

type UserList = {
    id: number;
    name: string;
    email: string;
    avatar: string | null;
    created_at: string;
};

type PaginatedUsers = {
    data: UserList[];
    links: any[];
};

defineProps<{
    users: PaginatedUsers;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: route('users.index'),
    },
];

const { getInitials } = useInitials();

const deleteUser = (user: UserList) => {
    if (confirm(`Tem certeza que deseja excluir o usuário ${user.name}?`)) {
        router.delete(route('users.destroy', user.id));
    }
};
</script>

<template>
    <Head title="Usuários" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Usuários</h1>
                <Button as-child>
                    <Link :href="route('users.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Usuário
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Lista de Usuários</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Usuário</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">E-mail</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Criado em</th>
                                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                <tr v-for="user in users.data" :key="user.id" class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle">
                                        <div class="flex items-center gap-3">
                                            <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
                                                <AvatarFallback class="rounded-lg bg-secondary text-secondary-foreground text-xs">
                                                    {{ getInitials(user.name) }}
                                                </AvatarFallback>
                                            </Avatar>
                                            <span class="font-medium">{{ user.name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 align-middle">{{ user.email }}</td>
                                    <td class="p-4 align-middle">{{ user.created_at }}</td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button variant="ghost" size="icon-sm" as-child>
                                                <Link :href="route('users.edit', user.id)">
                                                    <Pencil class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="icon-sm" @click="deleteUser(user)" class="text-destructive hover:text-destructive">
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="users.data.length === 0">
                                    <td colspan="4" class="p-4 text-center text-muted-foreground">
                                        Nenhum usuário encontrado.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="users.links.length > 3" class="mt-4 flex justify-center gap-1">
                        <template v-for="(link, k) in users.links" :key="k">
                            <div v-if="link.url === null" class="px-3 py-1 text-sm text-muted-foreground border rounded" v-html="link.label"></div>
                            <Link v-else :href="link.url" class="px-3 py-1 text-sm border rounded hover:bg-accent" :class="{ 'bg-primary text-primary-foreground': link.active }" v-html="link.label"></Link>
                        </template>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
