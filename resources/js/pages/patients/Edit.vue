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
import patientsRoutes from '@/routes/patients';
import type { BreadcrumbItem } from '@/types';

type Patient = {
    id: number;
    name: string;
    email: string;
    cpf: string;
    birth_date: string;
    phone: string;
    address: string | null;
};

const props = defineProps<{
    patient?: Patient;
}>();

const isEditing = !!props.patient;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pacientes',
        href: patientsRoutes.index.url(),
    },
    {
        title: isEditing ? 'Editar Paciente' : 'Novo Paciente',
        href: isEditing ? patientsRoutes.edit.url(props.patient!.id) : patientsRoutes.create.url(),
    },
];

const form = useForm({
    name: props.patient?.name ?? '',
    email: props.patient?.email ?? '',
    cpf: props.patient?.cpf ?? '',
    birth_date: props.patient?.birth_date ?? '',
    phone: props.patient?.phone ?? '',
    address: props.patient?.address ?? '',
});

const submit = () => {
    if (isEditing) {
        form.put(patientsRoutes.update.url(props.patient!.id));
    } else {
        form.post(patientsRoutes.store.url());
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Editar Paciente' : 'Novo Paciente'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon-sm" as-child>
                    <Link :href="patientsRoutes.index.url()">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <h1 class="text-2xl font-bold">{{ isEditing ? 'Editar Paciente' : 'Novo Paciente' }}</h1>
            </div>

            <div class="max-w-2xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Dados do Paciente</CardTitle>
                        <CardDescription>
                            {{ isEditing ? 'Atualize as informações do paciente.' : 'Preencha os campos para cadastrar um novo paciente.' }}
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
                                    placeholder="Nome do paciente"
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
                                    <Label for="cpf">CPF (somente números)</Label>
                                    <Input
                                        id="cpf"
                                        type="text"
                                        v-model="form.cpf"
                                        placeholder="000.000.000-00"
                                        maxlength="11"
                                        :error="!!form.errors.cpf"
                                        required
                                    />
                                    <InputError :message="form.errors.cpf" />
                                </div>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="birth_date">Data de Nascimento</Label>
                                    <Input
                                        id="birth_date"
                                        type="date"
                                        v-model="form.birth_date"
                                        :error="!!form.errors.birth_date"
                                        required
                                    />
                                    <InputError :message="form.errors.birth_date" />
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
                                    <Link :href="patientsRoutes.index.url()">Cancelar</Link>
                                </Button>
                                <Button type="submit" :disabled="form.processing">
                                    <template v-if="form.processing">
                                        <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                                        Salvando...
                                    </template>
                                    <template v-else>
                                        <Save class="mr-2 h-4 w-4" />
                                        {{ isEditing ? 'Atualizar' : 'Salvar' }} Paciente
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
