<template>
    <AppLayoutAdmin :currentTab="'tab-0'">
        <template v-slot:main-full>
            <div class="mt-[12px] font-bold text-[20px] uppercase text-[#9f224e]">
                Thống kê
            </div>
            <div class="mb-[20px] mt-[12px] border-[3px] border-[#9f224e]"></div>
            <div class="mt-[12px] flex items-center justify-center text-[15px]">
                <div class="w-[150px] text-center py-[6px] cursor-pointer border-[#9f224e] border-[2px]"
                 :class="{ 'bg-[#9f224e] text-white' : tab == 'tab-0', 'bg-[#fff]' : tab != 'tab-0' }" @click="changeTab('tab-0')">
                    Người dùng
                </div>
                <div class="ml-[38px] w-[150px] text-center py-[6px] cursor-pointer border-[#9f224e] border-[2px]"
                 :class="{ 'bg-[#9f224e] text-white' : tab == 'tab-1', 'bg-[#fff]' : tab != 'tab-1' }" @click="changeTab('tab-1')">
                    Bài viết
                </div>
                <div class="ml-[38px] w-[150px] text-center py-[6px] cursor-pointer border-[#9f224e] border-[2px]"
                 :class="{ 'bg-[#9f224e] text-white' : tab == 'tab-2', 'bg-[#fff]' : tab != 'tab-2' }" @click="changeTab('tab-2')">
                    Chủ đề
                </div>
            </div>
            <div class="my-[24px] flex items-center">
                <div class="text-[16px] mr-[16px]">Chọn mốc thời gian: </div>
                <el-date-picker
                    v-model="filters.mouthDate"
                    type="month"
                    start-placeholder="Chọn tháng thống kê"
                    :size="small"
                    format="MM-YYYY"
                    value-format="MM-YYYY"
                    class="max-w-[260px] mx-[28px]"
                    @change="fetchData()"
                />
            </div>
            <template v-if="this.tab == 'tab-0'">
                <div class="mt-[32px] w-[100%]">
                    <Bar
                        v-if="loaded"
                        :options="accountChartOptions"
                        :data="accountChartData"
                        height="120"
                    />
                </div>
            </template>
            <template v-if="this.tab == 'tab-1'">
                <div class="mt-[32px] w-[100%]">
                    <Bar
                        v-if="loaded"
                        :options="postChartOptions"
                        :data="postChartData"
                        height="120"
                    />
                </div>
            </template>
            <template v-if="this.tab == 'tab-2'">
                <div class="mt-[32px] w-[100%]">
                    <Pie
                        v-if="loaded"
                        :options="categoryChartOptions"
                        :data="categoryChartData"
                    />
                </div>
            </template>
        </template>
    </AppLayoutAdmin>
</template>
<script>
import AppLayoutAdmin from '@/Layouts/AppLayoutAdmin.vue'
import { Link } from '@inertiajs/vue3'
import DataTable from '@/Components/UI/DataTable.vue'
import moment from "moment";
import axios from 'axios'
import { Bar, Pie } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)

export default{
    components:{
        AppLayoutAdmin,
        Link,
        Bar,
        Pie,
        DataTable
    },
    data() {
        return {
            tab: 'tab-0',
            filters: {
                mouthDate: moment().startOf('month').format('MM-YYYY')
            },
            accountFields: [
                { key: 'id', label: 'ID', align: 'center', width: 120},
                { key: 'code', label: 'Code', align: 'center', width: 150},
                { key: 'email', label: 'Email', align: 'center'},
                { key: 'userable_type', label: 'Phân loại', align: 'center', width: 200},
                { key: 'created_at', label: 'Ngày tạo', align: 'center', width: 170 },
                { key: 'updated_at', label: 'Ngày cập nhật', align: 'center', width: 170 },
            ],
            tableData: [],
            showTable: false,
            loaded: false,
            accountCountData: {
                readers: 0,
                creators: 0,
                sum: 0
            },
            postCountData: {
                post_approved: 0,
                post_unapproved: 0,
                sum: 0
            },
            categoryCountData: {},
            accountChartData: {
                labels: [
                ],
                datasets: [
                    {
                        label: "Độc giả",
                        pointBackgroundColor: "white",
                        borderWidth: 1,
                        pointBorderColor: "white",
                        backgroundColor: "blue",
                        data: []
                    },
                    {
                        label: "Tác giả",
                        pointBackgroundColor: "white",
                        pointBorderColor: "white",
                        borderWidth: 1,
                        backgroundColor: "red",
                        data: []
                    }
                ],
                dataValue: []
            },
            accountChartOptions: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                    },
                    y: {
                        min: 0,
                        max: 10,
                    }
                },
                onClick: (event, element) => {
                    if(element.length > 0) {
                        this.showTable = true
                        const row = element[0].datasetIndex
                        const column = element[0].index
                        this.tableData = this.accountChartData.dataValue[row][column]
                    }
                }
            },
            postChartData: {
                labels: [
                ],
                datasets: [
                    {
                        label: "Đã xác nhận",
                        pointBackgroundColor: "white",
                        borderWidth: 1,
                        pointBorderColor: "white",
                        backgroundColor: "blue",
                        data: []
                    },
                    {
                        label: "Chưa xác nhận",
                        pointBackgroundColor: "white",
                        pointBorderColor: "white",
                        borderWidth: 1,
                        backgroundColor: "red",
                        data: []
                    }
                ],
                dataValue: []
            },
            postChartOptions: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                    },
                    y: {
                        min: 0,
                        max: 10,
                    }
                },
                // onClick: (event, element) => {
                //     if(element.length > 0) {
                //         this.showTable = true
                //         const row = element[0].datasetIndex
                //         const column = element[0].index
                //         this.tableData = this.postChartData.dataValue[row][column]
                //     }
                // }
            },
            categoryChartData: {
                labels: [],
                datasets: [
                    {
                        backgroundColorText: [
                            'bg-[#41B883]', 'bg-[#E46651]', 'bg-[#ffc107]', 'bg-[#00D8FF]', 'bg-[#DD1B16]', 'bg-[#6f42c1]',
                            'bg-[#F46B9C]', 'bg-[#6610f2]', 'bg-[#dc3545]' , 'bg-[#198754]', 'bg-[#212529]'],
                        backgroundColor: [
                            '#41B883', '#E46651', '#ffc107', '#00D8FF', '#DD1B16', '#6f42c1', '#F46B9C', 
                            '#6610f2', '#dc3545', '#198754', '#212529'],
                        data: []
                    }
                ]
            },
            categoryChartOptions: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    },
    created() {
        this.fetchData()
        document.title = `Thống kê - Admin hệ thống`
    },
    methods: {
        moment,
        changeTab(tab) {
            this.tab = tab
            this.fetchData()
        },
        fetchData() {
            this.loaded = false
            this.showTable = false
            const pagram = {
                ...this.filters
            }
            if(this.tab == 'tab-0') {
                axios.get(route('admin.get-chart-account', pagram))
                    .then(reponse => {
                        this.accountChartData.datasets[0].data = reponse.data.readers.dataPeriod
                        this.accountChartData.datasets[1].data = reponse.data.creators.dataPeriod
                        this.accountChartData.dataValue[0] = reponse.data.readers.formData
                        this.accountChartData.dataValue[1] = reponse.data.creators.formData
                        this.accountChartData.labels = reponse.data.readers.titleBottomChart
                        this.accountCountData.readers = reponse.data.count_data.readers
                        this.accountCountData.creators = reponse.data.count_data.creators
                        this.accountCountData.sum = this.accountCountData.creators + this.accountCountData.readers
                        this.accountChartOptions.scales.y.max = reponse.data.count_data.sum
                        this.loaded = true
                    })
            }
            else if(this.tab == 'tab-1') {
                axios.get(route('admin.get-chart-post', pagram))
                    .then(reponse => {
                        this.postChartData.datasets[0].data = reponse.data.post_approved.dataPeriod
                        this.postChartData.datasets[1].data = reponse.data.post_unapproved.dataPeriod
                        this.postChartData.dataValue[0] = reponse.data.post_approved.formData
                        this.postChartData.dataValue[1] = reponse.data.post_unapproved.formData
                        this.postChartData.labels = reponse.data.post_approved.titleBottomChart
                        this.postCountData.post_approved = reponse.data.count_data.post_approved
                        this.postCountData.post_unapproved = reponse.data.count_data.post_unapproved
                        this.postCountData.sum = this.postCountData.post_approved + this.postCountData.post_unapproved
                        this.postChartOptions.scales.y.max = reponse.data.count_data.sum
                        this.loaded = true
                    })
            }
            else if(this.tab == 'tab-2') {
                axios.get(route('admin.get-chart-category', pagram))
                    .then(reponse => {
                        this.categoryChartData.datasets[0].data = reponse.data.dataPeriod
                        this.categoryChartData.labels = reponse.data.titleBottomChart
                        this.categoryCountData.sum = reponse.data.sum
                        this.loaded = true
                    })
            }
        }
    }
}
</script>

<style>
    .el-date-editor {
        margin: 0;
    }
</style>
  