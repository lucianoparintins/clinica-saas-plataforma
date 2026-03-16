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
import doctorsRoutes from '@/routes/doctors';
import type { BreadcrumbItem } from '@/types';

type DoctorList = {
    id: number;
    name: string;
    email: string;
    crm: string;
    specialty: string;
    phone: string;
    created_at: string;
};

type PaginatedDoctors = {
    data: DoctorList[];
    links: any[];
};

defineProps<{
    doctors: PaginatedDoctors;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Médicos',
        href: doctorsRoutes.index.url(),
    },
];

const deleteDoctor = (doctor: DoctorList) => {
    if (confirm(`Tem certeza que deseja excluir o médico ${doctor.name}?`)) {
        router.delete(doctorsRoutes.destroy.url(doctor.id));
    }
};
</script>

<template>
    <Head title="Médicos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Médicos</h1>
                <Button as-child>
                    <Link :href="doctorsRoutes.create.url()">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Médico
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Lista de Médicos</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nome</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">CRM</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Especialidade</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Telefone</th>
                                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                <tr v-for="doctor in doctors.data" :key="doctor.id" class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle font-medium">{{ doctor.name }}</td>
                                    <td class="p-4 align-middle">{{ doctor.crm }}</td>
                                    <td class="p-4 align-middle">{{ doctor.specialty }}</td>
                                    <td class="p-4 align-middle">{{ doctor.phone }}</td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button variant="ghost" size="icon-sm" as-child>
                                                <Link :href="doctorsRoutes.show.url(doctor.id)">
                                                    <Eye class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="icon-sm" as-child>
                                                <Link :href="doctorsRoutes.edit.url(doctor.id)">
                                                    <Pencil class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="icon-sm" @click="deleteDoctor(doctor)" class="text-destructive hover:text-destructive">
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="doctors.data.length === 0">
                                    <td colspan="5" class="p-4 text-center text-muted-foreground">
                                        Nenhum médico encontrado.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="doctors.links.length > 3" class="mt-4 flex justify-center gap-1">
                        <template v-for="(link, k) in doctors.links" :key="k">
                            <div v-if="link.url === null" class="px-3 py-1 text-sm text-muted-foreground border rounded" v-html="link.label"></div>
                            <Link v-else :href="link.url" class="px-3 py-1 text-sm border rounded hover:bg-accent" :class="{ 'bg-primary text-primary-foreground': link.active }" v-html="link.label"></Link>
                        </template>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
