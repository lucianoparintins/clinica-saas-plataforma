<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Eye } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import patientsRoutes from '@/routes/patients';
import type { BreadcrumbItem } from '@/types';

type PatientList = {
    id: number;
    name: string;
    email: string;
    cpf: string;
    phone: string;
    created_at: string;
};

type PaginatedPatients = {
    data: PatientList[];
    links: any[];
};

defineProps<{
    patients: PaginatedPatients;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pacientes',
        href: patientsRoutes.index.url(),
    },
];

const deletePatient = (patient: PatientList) => {
    if (confirm(`Tem certeza que deseja excluir o paciente ${patient.name}?`)) {
        router.delete(patientsRoutes.destroy.url(patient.id));
    }
};
</script>

<template>
    <Head title="Pacientes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Pacientes</h1>
                <Button as-child>
                    <Link :href="patientsRoutes.create.url()">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Paciente
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Lista de Pacientes</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nome</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">CPF</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Telefone</th>
                                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                <tr v-for="patient in patients.data" :key="patient.id" class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle font-medium">{{ patient.name }}</td>
                                    <td class="p-4 align-middle">{{ patient.cpf }}</td>
                                    <td class="p-4 align-middle">{{ patient.phone }}</td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button variant="ghost" size="icon-sm" as-child>
                                                <Link :href="patientsRoutes.show.url(patient.id)">
                                                    <Eye class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="icon-sm" as-child>
                                                <Link :href="patientsRoutes.edit.url(patient.id)">
                                                    <Pencil class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="icon-sm" @click="deletePatient(patient)" class="text-destructive hover:text-destructive">
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="patients.data.length === 0">
                                    <td colspan="4" class="p-4 text-center text-muted-foreground">
                                        Nenhum paciente encontrado.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="patients.links.length > 3" class="mt-4 flex justify-center gap-1">
                        <template v-for="(link, k) in patients.links" :key="k">
                            <div v-if="link.url === null" class="px-3 py-1 text-sm text-muted-foreground border rounded" v-html="link.label"></div>
                            <Link v-else :href="link.url" class="px-3 py-1 text-sm border rounded hover:bg-accent" :class="{ 'bg-primary text-primary-foreground': link.active }" v-html="link.label"></Link>
                        </template>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
