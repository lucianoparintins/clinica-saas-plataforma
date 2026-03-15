<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save, Loader2 } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import type { BreadcrumbItem, User } from '@/types';

const props = defineProps<{
    user?: User;
}>();

const isEditing = !!props.user;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: route('users.index'),
    },
    {
        title: isEditing ? 'Editar Usuário' : 'Novo Usuário',
        href: isEditing ? route('users.edit', props.user!.id) : route('users.create'),
    },
];

const form = useForm({
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    if (isEditing) {
        form.put(route('users.update', props.user!.id), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    } else {
        form.post(route('users.store'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Editar Usuário' : 'Novo Usuário'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon-sm" as-child>
                    <Link :href="route('users.index')">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <h1 class="text-2xl font-bold">{{ isEditing ? 'Editar Usuário' : 'Novo Usuário' }}</h1>
            </div>

            <div class="max-w-2xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Informações do Usuário</CardTitle>
                        <CardDescription>
                            {{ isEditing ? 'Atualize os dados do usuário selecionado.' : 'Preencha os dados para cadastrar um novo usuário.' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="space-y-2">
                                <Label for="name">Nome</Label>
                                <Input
                                    id="name"
                                    type="text"
                                    v-model="form.name"
                                    placeholder="Nome completo"
                                    :error="!!form.errors.name"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="space-y-2">
                                <Label for="email">E-mail</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    placeholder="exemplo@email.com"
                                    :error="!!form.errors.email"
                                    required
                                />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="space-y-2">
                                <Label for="password">Senha {{ isEditing ? '(deixe em branco para não alterar)' : '' }}</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    v-model="form.password"
                                    placeholder="••••••••"
                                    :error="!!form.errors.password"
                                    :required="!isEditing"
                                />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div class="space-y-2">
                                <Label for="password_confirmation">Confirmar Senha</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    v-model="form.password_confirmation"
                                    placeholder="••••••••"
                                    :error="!!form.errors.password_confirmation"
                                    :required="!isEditing && !!form.password"
                                />
                                <InputError :message="form.errors.password_confirmation" />
                            </div>

                            <div class="flex justify-end gap-3 border-t pt-6">
                                <Button type="button" variant="ghost" as-child>
                                    <Link :href="route('users.index')">Cancelar</Link>
                                </Button>
                                <Button type="submit" :disabled="form.processing">
                                    <template v-if="form.processing">
                                        <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                                        Salvando...
                                    </template>
                                    <template v-else>
                                        <Save class="mr-2 h-4 w-4" />
                                        {{ isEditing ? 'Atualizar' : 'Criar' }} Usuário
                                    </template>
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
