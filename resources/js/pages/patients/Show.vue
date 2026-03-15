<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Pencil } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
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
    created_at: string;
};

const props = defineProps<{
    patient: Patient;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pacientes',
        href: patientsRoutes.index.url(),
    },
    {
        title: 'Ver Paciente',
        href: patientsRoutes.show.url(props.patient.id),
    },
];
</script>

<template>
    <Head title="Ver Paciente" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon-sm" as-child>
                        <Link :href="patientsRoutes.index.url()">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <h1 class="text-2xl font-bold">Detalhes do Paciente</h1>
                </div>
                <Button as-child variant="outline">
                    <Link :href="patientsRoutes.edit.url(patient.id)">
                        <Pencil class="mr-2 h-4 w-4" />
                        Editar
                    </Link>
                </Button>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Informações Pessoais</CardTitle>
                        <CardDescription>Dados básicos e identificação.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Nome Completo</label>
                            <p class="text-lg font-semibold">{{ patient.name }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">CPF</label>
                                <p class="font-medium">{{ patient.cpf }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Data de Nascimento</label>
                                <p class="font-medium">{{ patient.birth_date }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Cadastrado em</label>
                            <p class="font-medium text-muted-foreground">{{ patient.created_at }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Contato e Endereço</CardTitle>
                        <CardDescription>Como localizar o paciente.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">E-mail</label>
                            <p class="font-medium">{{ patient.email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Telefone</label>
                            <p class="font-medium">{{ patient.phone }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Endereço</label>
                            <p class="font-medium">{{ patient.address || 'Não informado' }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
