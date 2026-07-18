<script setup lang="ts">
import { ref, onMounted,onUnmounted,computed  } from 'vue';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
import axios from 'axios';
import { Link,usePage } from '@inertiajs/vue3';
import InviteModal from '@/components/InviteModal.vue';
import GenerateShortUrlModal from '@/components/GenerateShortUrlModal.vue';
import { toast } from 'vue-sonner';



const page = usePage();

// auth users  
const auth = computed(() => page.props.auth);



const form = ref({
    name: '',
    email: '',
});

const errors = ref({});
const loading = ref(false);

const submit = async () => {
    loading.value = true;
    errors.value = {};

    try {
        const response = await axios.post('/company/store', form.value);
        toast.success(response.data.message);

        showModal.value = false;

        form.value = {
            name: '',
            email: '',
        };

    } catch (error: any) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            toast.error(error.response?.data?.message || 'Something went wrong.');
        }
    } finally {
        loading.value = false;
    }
};

DataTable.use(DataTablesCore);

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: '/dashboard',
            },
        ],
    },
});

const showModal = ref(false);


const props = defineProps<{
    companies: {
        data: any[];
        links: any[];
        current_page: number;
        last_page: number;
        total: number;
    };
}>();


const columns = [
    {
        data: null,
        title: 'S.No',
        orderable: false,
        searchable: false,
        render: (data: any, type: any, row: any, meta: any) => {
            return (
                (props.companies.current_page - 1) * props.companies.per_page +
                meta.row +
                1
            );
        },
    },
    { data: 'name', title: 'Company Name' },
      {
        data: 'role',
        title: 'Role',
        orderable: false,
        searchable: false,
        render: (data: any, type: any, row: any, meta: any) => {
            return ['Admin', 'Member'].includes(row.role)
            ? row.role
            : '-';
        },
    },
    {
        data: null,
        title: 'Actions',
        orderable: false,
        searchable: false,
        render: (data: any, type: any, row: any) => {
            let inviteButton = '';
            let generateShortUrlButton = '';
            console.log('role', row.role);
            if (row.role != 'Member') {
                inviteButton = `
                    <button
                        class="invite-btn px-4 py-2 bg-primary text-white rounded hover:bg-primary-100"
                        data-id="${row.id}"
                        data-name="${row.name}"
                    >
                        Invite Users
                    </button>
                `;
            }

            if (row.role === 'Member' || row.role == 'Admin') {
                generateShortUrlButton = `
                    <button
                        class="generate-short-btn px-4 py-2 bg-primary text-white rounded hover:bg-primary-100"
                        data-id="${row.id}"
                        data-name="${row.name}"
                    >
                         Generate Short Url
                    </button>
                `;
            }



            return `
                
                <div class="flex items-center gap-3">

                ${inviteButton}
                ${generateShortUrlButton}
                  
                </div>
            `;
        },
    },
];


// invitation modal 
const showGenerateShortUrlModal = ref(false);


const openGenerateShortUrlModal = (id: number, name: string) => {
    companyId.value = id;
    companyName.value = name;               
    showGenerateShortUrlModal.value = true;
};

const closeGenerateShortUrlModal = () => {
    showGenerateShortUrlModal.value = false;
    companyId.value = null;
    companyName.value = '';
};

const generateShortUrl = () => {
    if (!companyId.value) return;
    console.log('Inviting company ID:', companyId.value);
    closeGenerateShortUrlModal();
};

const handleClickGenerateShortUrl = (event: MouseEvent) => {
    const target = event.target as HTMLElement;

    const generateShortUrlBtn = target.closest('.generate-short-btn') as HTMLElement | null;

    if (!generateShortUrlBtn) return;

    const id = Number(generateShortUrlBtn.dataset.id);
    const cName = generateShortUrlBtn.dataset.name;

    if (!isNaN(id)) {
        openGenerateShortUrlModal(id, cName || '');
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickGenerateShortUrl);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickGenerateShortUrl);
});

// GenerateUrl Modal 
// invitation modal 
const showInviteModal = ref(false);
const companyId = ref<number | null>(null);
const companyName = ref('');

const openInviteModal = (id: number, name: string) => {
    companyId.value = id;
    companyName.value = name;   
    console.log('Opening Invite Modal for Company ID:', id, 'Company Name:', name);            
    showInviteModal.value = true;
};

const closeInviteModal = () => {
    showInviteModal.value = false;
    companyId.value = null;
    companyName.value = '';
};

const inviteCompany = () => {
    if (!companyId.value) return;
    console.log('Inviting company ID:', companyId.value);
    closeInviteModal();
};

const handleClickInviteBtn = (event: MouseEvent) => {
    const target = event.target as HTMLElement;

    const inviteBtn = target.closest('.invite-btn') as HTMLElement | null;

    if (!inviteBtn) return;

    const id = Number(inviteBtn.dataset.id);
    const cName = inviteBtn.dataset.name;

    if (!isNaN(id)) {
        openInviteModal(id, cName || '');
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickInviteBtn);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickInviteBtn);
});
</script>

<template>
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">Dashboard <p> You are default Login as role <span class="text-blue-600">{{ auth.role }}</span>  <span  v-if="auth.owned_company" class="text-blue-600"> of Company {{ auth.owned_company?.name }}</span></p></h1>

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Companies</h2>

                <!-- <a
                    :href="`/company/short-url`"
                    title="View Short URLs"
                    class="px-4 py-2 bg-primary text-white rounded hover:bg-primary-100"
                >  
                    View Short URLs
                </a> -->


            <button v-if="auth.role == 'SuperAdmin'"
                @click="showModal = true"
                class="px-4 py-2 bg-primary text-white rounded hover:bg-primary-100"
            >
                Add Company 
            </button>
        </div>

        <DataTable
            :data="companies.data"
            :columns="columns"
            :options="{
                paging: false,
                searching: false,
                info: false
            }"
            class="display"
        />

        <!-- 2. Laravel / Inertia Pagination Controls -->
       <div v-if="companies.links.length > 3" class="flex flex-wrap justify-end mt-6">
            <template v-for="(link, key) in companies.links" :key="key">
                
                <!-- Disabled Link -->
                <div 
                    v-if="link.url === null" 
                    class="mr-1 mb-1 px-4 py-2 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label" 
                />
                
                <!-- Clickable Inertia Link -->
                <Link 
                    v-else 
                    class="mr-1 mb-1 px-4 py-2 text-sm leading-4 border rounded hover:bg-gray-100 focus:border-indigo-500"
                    :class="{ 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700': link.active }"
                    :href="link.url" 
                    v-html="link.label" 
                />
                
            </template>
        </div>

         <!-- Modal -->
        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        >
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Add Company</h3>

                    <button
                        @click="showModal = false"
                        class="text-gray-500 text-2xl"
                    >
                        &times;
                    </button>
                </div>

                <div class="space-y-4 mb-4">
                    <lable>Company Name</lable>
                    <input  v-model="form.name"
                        type="text"
                        placeholder="Company Name"
                        class="w-full border rounded p-2"
                    />
                    <span
                        v-if="errors.name"
                         class="text-red-500 text-sm mt-1"
                    >
                     {{ errors.name[0] }}
                    </span>
                </div>

                <div class="space-y-4 mt-4">
                    <lable>Email</lable>
                    <input v-model="form.email"
                        type="text"
                        placeholder="Email"
                        class="w-full border rounded p-2"
                    />
                    <span
                          v-if="errors.email"
                         class="text-red-500 text-sm mt-1"
                    >
                     {{ errors.email[0] }}
                    </span>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button
                        @click="showModal = false"
                        class="px-4 py-2 border rounded"
                    >
                        Cancel
                    </button>

                   <button
                        @click="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-primary text-white rounded"
                    >
                        {{ form.processing ? 'Saving...' : 'Save' }}
                    </button>
                </div>

            </div>
        </div>

    </div>

    <InviteModal
    :show="showInviteModal"
    :company-id="companyId"
    :company-name="companyName"
    @close="showInviteModal = false"
    @save="submitInvite"
    />

    <GenerateShortUrlModal
    :show="showGenerateShortUrlModal"
    :company-id="companyId"
    :company-name="companyName"
    @close="showGenerateShortUrlModal = false"
    @save="submitGenerateShortUrl"
    />
</template>

