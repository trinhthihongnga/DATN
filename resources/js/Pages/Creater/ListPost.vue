<template>
    <AppLayout :current-tab="'main-full'">
        <template v-slot:main-full>
            <!-- <div class="min-w-[280px] w-[280px]">
                <Navigation :currentTab="'tab-4'"/>
            </div> -->
            <div class="flex-auto ml-[16px] border-[1px]">
                <div class="py-[20px] bg-[#fff] box-shadow">
                    <div class="border-b-[2px] border-[#9f224e]">
                        <div class="border-l-[5px] border-[#9f224e] text-[#9f224e] font-bold ml-[8px] pl-[8px] mb-[12px] text-[17px]">
                            Danh sách bài viết của bạn
                        </div>
                    </div>
                    <div class="flex mx-[12px] mt-[14px] mb-[24px] text-[15px] font-bold">
                        <div class="mr-[8px] w-[130px] border-[2px] text-center py-[6px] rounded-[4px] border-[#dee2e6] cursor-pointer"
                         :class="{ 'bg-[#9f224e] text-white' : tab == 'tab-1' }"
                         @click="changeTab('tab-1')">
                           Chưa duyệt
                        </div>
                        <div class="w-[130px] border-[2px] text-center py-[6px] rounded-[4px] border-[#dee2e6] cursor-pointer"
                         :class="{ 'bg-[#9f224e] text-white' : tab == 'tab-0' }"
                         @click="changeTab('tab-0')">
                            Đã duyệt
                        </div>

                    </div>
                    <div class="mt-[12px] mx-[12px] flex text-[16px]">
                        <el-select v-model="filter.limit" class="max-w-[60px] mr-[18px]" @change="changeSearch">
                            <el-option
                                v-for="item in options"
                                :key="item"
                                :label="item"
                                :value="item"
                            />
                        </el-select>
                        <el-input class="max-w-[300px]" v-model="filter.search"
                                placeholder="Nhập từ khóa" clearable @keyup="changeSearch"/>
                        <div class="ml-[32px] w-[150px] text-center border-[2px] rounded-[4px] cursor-pointer mr-[2px] bg-[#9f224e] text-[15px] 
                            text-[#fff] active:scale-95 h-[36px] leading-[34px]" @click="addPost()">
                            Thêm bài viết
                        </div>
                    </div>
                    <template v-if="postsData.length == 0">
                        <div class="text-center text-[16px] mt-[52px] mb-[58px] mb-[6px]">
                            Không có bài viết nào
                        </div>
                    </template>
                    <template v-else>
                        <template v-if="filter.is_approved">
                            <div class="mt-[12px] mb-[16px] mx-[12px] grid grid-cols-4 gap-3">
                                <div v-for="item in postsData" class="mt-[8px] relative post-item">
                                    <Link :href="route('post', item.slug)">
                                        <img :src="item.image" alt="" class="post-image w-[100%] h-[150px] object-cover">
                                    </Link>
                                    <div class="px-[2px]">
                                        <Link :href="route('post', item.slug)">
                                            <div class="post-title font-bold text-[15px] mt-[8px]">{{ item.title }}</div>
                                        </Link>
                                        <div class="flex items-center mt-[8px]">
                                            <Link :href="route('list-category', item.categorySlug)">
                                                <div class="text-[14px] font-bold text-[#076db6]">{{ item.categoryName }}</div>
                                            </Link>
                                            <div class="ml-[16px] text-[13px]">{{ convertTime(item.created_at) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="mt-[12px] mb-[16px] mx-[12px] grid grid-cols-4 gap-3">
                                <div v-for="item in postsData" class="mt-[8px] relative post-item cursor-pointer" @click="editPost(item)">
                                    <img :src="item.image" alt="" class="post-image w-[100%] h-[150px] object-cover">
                                    <div class="px-[2px]">
                                        <div class="post-title font-bold text-[15px] mt-[8px]">{{ item.title }}</div>
                                        <div class="flex items-center mt-[12px]">
                                            <Link :href="route('list-category', item.categorySlug)">
                                                <div class="text-[14px] font-bold text-[#076db6]">{{ item.categoryName }}</div>
                                            </Link>
                                            <div class="ml-[16px] text-[13px]">{{ convertTime(item.created_at) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="flex mt-[24px] mb-[64px] justify-center">
                            <Paginate @page-change="handleCurrentPage" :paginate="paginate" :current-page="filter.page || 1"
                              paginate-background/>
                        </div>
                    </template>
                </div>
            </div>
            <ShowPostForm ref="showPostForm"/>
            <AddPostForm ref="formPostForm" @change-post="fetchData"/>
            <EditPostForm ref="editPostForm" @change-post="fetchData"/>
        </template>
    </AppLayout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Paginate from "@/Components/UI/Paginate.vue";
import { Link } from '@inertiajs/vue3'
import ShowPostForm from './Dialog/ShowPost.vue'
import AddPostForm from './Dialog/AddPost.vue'
import EditPostForm from './Dialog/EditPost.vue'
import { ElMessage } from 'element-plus'
import moment from "moment";

export default{
    components:{
        AppLayout,
        Paginate,
        ShowPostForm,
        AddPostForm,
        EditPostForm,
        Link,
    },
    data() {
        return {
            currentTab: 'tab-2',
            options: [12, 20, 28],
            tab: 'tab-1',
            filter: {
                limit: 12,
                categorySelect: [],
                search: '',
                page: 1,
                is_approved: false,
            },
            paginate: [],
            postsData: []
        }  
    },
    created() {
        this.fetchData()
        document.title = `Danh sách bài viết`
    },
    methods: {
        moment,
        async fetchData() {
            const pagram = { ...this.filter }
            const response = await axios.get(route('creator.posts.index', pagram));
            this.postsData = response.data.data
            this.paginate = response.data.meta
        },
        handleCurrentPage(value) {
            this.filter.page = value
            this.fetchData()
        },
        showPost(postItem) {
            this.$refs.showPostForm.open(postItem)
        },
        addPost() {
            this.$refs.formPostForm.open()
        },
        editPost(postItem) {
            this.$refs.editPostForm.open(postItem.id)
        },
        changeSearch() {
            this.fetchData()
        },
        changeTab(tab) {
            this.tab = tab
            if(tab == 'tab-0') {
                this.filter.is_approved = true
            }
            else {
                this.filter.is_approved = false
            }
            this.filter.limit = 12
            this.filter.categorySelect = []
            this.filter.search = ''
            this.filter.page = 1
            this.filter.paginate = []
            this.postsData = []
            this.fetchData()
        },
        deletePost(post) {
            axios.delete(route('creator.posts.destroy', post.id))
                .then(response => {
                    this.fetchData()
                    ElMessage({
                        showClose: true,
                        message: 'Xóa bài viết thành công',
                        type: 'success',
                    })
                })
                .catch(error => {
                    ElMessage({
                        showClose: true,
                        message: error.response.data.message,
                        type: 'error',
                    })
                })
        },
        convertTime(created_at) {
            const now = moment()
            const time = moment(created_at)

            if (now.diff(time, 'years') > 0) {
                return `${now.diff(time, 'years')} năm trước`
            }
            else if (now.diff(time, 'months') > 0) {
                return `${now.diff(time, 'months')} tháng trước`
            }
            else if (now.diff(time, 'days') > 0) {
                return `${now.diff(time, 'days')} ngày trước`
            }
            else if (now.diff(time, 'hours') > 0) {
                return `${now.diff(time, 'hours')} giờ trước`
            }
            else if (now.diff(time, 'minutes') > 0) {
                return `${now.diff(time, 'minutes')} phút trước`
            }
            else if (now.diff(time, 'seconds') > 0) {
                return `${now.diff(time, 'seconds')} giây trước`
            }
        }
    }
}
</script>
<style>
.post-title {
  height: 40px;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
}
</style>