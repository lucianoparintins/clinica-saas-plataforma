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
import doctorsRoutes from '@/routes/doctors';
import type { BreadcrumbItem } from '@/types';

type Doctor = {
    id: number;
    name: string;
    email: string;
    crm: string;
    specialty: string;
    phone: string;
    address: string | null;
};

const props = defineProps<{
    doctor?: Doctor;
}>();

const isEditing = !!props.doctor;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Médicos',
        href: doctorsRoutes.index.url(),
    },
    {
        title: isEditing ? 'Editar Médico' : 'Novo Médico',
        href: isEditing ? doctorsRoutes.edit.url(props.doctor!.id) : doctorsRoutes.create.url(),
    },
];

const form = useForm({
    name: props.doctor?.name ?? '',
    email: props.doctor?.email ?? '',
    crm: props.doctor?.crm ?? '',
    specialty: props.doctor?.specialty ?? '',
    phone: props.doctor?.phone ?? '',
    address: props.doctor?.address ?? '',
});

const submit = () => {
    if (isEditing) {
        form.put(doctorsRoutes.update.url(props.doctor!.id));
    } else {
        form.post(doctorsRoutes.store.url());
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Editar Médico' : 'Novo Médico'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon-sm" as-child>
                    <Link :href="doctorsRoutes.index.url()">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <h1 class="text-2xl font-bold">{{ isEditing ? 'Editar Médico' : 'Novo Médico' }}</h1>
            </div>

            <div class="max-w-2xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Dados do Médico</CardTitle>
                        <CardDescription>
                            {{ isEditing ? 'Atualize as informações do médico.' : 'Preencha os campos para cadastrar um novo médico.' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="space-y-2">
                                <Label for="name">Nome Completo</Label>
                                <Input
                                    id="name"
                                    type="text"
                                    v-model="form.name"
                                    placeholder="Nome do médico"
                                    :error="!!form.errors.name"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
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
                                    <Label for="crm">CRM</Label>
                                    <Input
                                        id="crm"
                                        type="text"
                                        v-model="form.crm"
                                        placeholder="CRM/UF 000000"
                                        maxlength="20"
                                        :error="!!form.errors.crm"
                                        required
                                    />
                                    <InputError :message="form.errors.crm" />
                                </div>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="specialty">Especialidade</Label>
                                    <Input
                                        id="specialty"
                                        type="text"
                                        v-model="form.specialty"
                                        placeholder="Ex: Cardiologia, Pediatria"
                                        :error="!!form.errors.specialty"
                                        required
                                    />
                                    <InputError :message="form.errors.specialty" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="phone">Telefone / Celular</Label>
                                    <Input
                                        id="phone"
                                        type="text"
                                        v-model="form.phone"
                                        placeholder="(00) 00000-0000"
                                        :error="!!form.errors.phone"
                                        required
                                    />
                                    <InputError :message="form.errors.phone" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="address">Endereço Completo</Label>
                                <Input
                                    id="address"
                                    type="text"
                                    v-model="form.address"
                                    placeholder="Rua, número, bairro, cidade - UF"
                                    :error="!!form.errors.address"
                                />
                                <InputError :message="form.errors.address" />
                            </div>

                            <div class="flex justify-end gap-3 border-t pt-6">
                                <Button type="button" variant="ghost" as-child>
                                    <Link :href="doctorsRoutes.index.url()">Cancelar</Link>
                                </Button>
                                <Button type="submit" :disabled="form.processing">
                                    <template v-if="form.processing">
                                        <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                                        Salvando...
                                    </template>
                                    <template v-else>
                                        <Save class="mr-2 h-4 w-4" />
                                        {{ isEditing ? 'Atualizar' : 'Salvar' }} Médico
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
