<template>
    <AppLayout>
        <template v-slot:main-1>
            <div class="main border-[1px] mb-[24px] relative box-shadow">
                <div class="heading text-[30px] font-bold text-[#9f224e]]">Thông tin bài viết</div>
                <div class="py-[12px] px-[12px] border-t-[2px] mt-[12px]">
                    <div class="text-[22px] font-bold">
                        {{ dataForm.title }}
                    </div>
                    <div class="flex items-center my-[12px]">
                        <Link :href="route('list-category', dataForm.categorySlug)">
                            <div class="text-[16px] text-[#076db6] font-bold">{{ dataForm.categoryName }}</div>
                        </Link>
                        <Link :href="route('creator', dataForm.creatorCode)">
                            <div class="ml-[18px] text-[16px] font-bold text-[#333]">
                                {{ dataForm.creator }}
                            </div>
                        </Link>
                        <div class="ml-[18px] text-[14px]">{{ convertTime(dataForm.created_at) }}</div>
                    </div>
                    <div class="mt-[8px]">
                        <div class="text-[16px] mb-[12px]">
                            {{ dataForm.description }}
                        </div>
                        <el-image
                            style="width: 100%; height: 100%;"
                            :src="dataForm.image"
                            :zoom-rate="1.2"
                            preview-teleported="true"
                            hide-on-click-modal="true"
                            :preview-src-list="[dataForm.image]"
                            :initial-index="1"
                            fit="cover"
                        />
                        <div class="text-[14px] text-center italic mx-[24px]">
                            {{ dataForm.description }}
                        </div>
                    </div>
                    <div v-html="dataForm.content" class="mt-[12px] text-[16px] mb-[18px]"></div>
                </div>
                <div class="py-[12px] pt-[18px] border-t-[2px]">
                    <div class="font-bold text-[17px]">Tất cả bình luận ({{totalcomment}})</div>
                    <div class="mt-[12px]">
                        <div class="mt-[18px] mx-[8px]">
                            <template v-if="comments.length > 0" v-for="comment in comments">
                                <div class="relative flex mb-[20px]" 
                                 :class="{ 'comment-active mb-[200px]' : idOpenFormComment == comment.id }">
                                    <Link :href="route('creator', comment.commentator_code)">
                                        <img v-if="comment.commentator_image" :src="comment.commentator_image" alt="" 
                                            class="w-[46px] min-w-[46px] h-[46px] rounded-[50%]">
                                        <div v-else class="bg-[#9f224e] w-[46px] h-[46px] border-[1px] rounded-[50%]
                                                flex justify-center items-center">
                                            <span class="text-[#fff] text-[14px]">{{
                                                comment.commentator[0] }}</span>
                                        </div>
                                    </Link>
                                    <div :id="comment.id" class="relative ml-[12px] mt-[-4px] pr-[52px] comment-load-more comment-hidden">
                                        <span class="font-bold text-[16px]">
                                            <Link :href="route('creator', comment.commentator_code)">
                                                <span v-if="dataForm.creatorCode == comment.commentator_code">Tác giả: </span>
                                                <span v-else>{{ comment.commentator }}: </span>
                                            </Link>
                                        </span>
                                        <!-- comment content -->
                                        <span class="text-[15px] whitespace-pre-line">
                                            <span>
                                                {{ comment.content }}
                                            </span>
                                        </span>
                                        <div class="mt-[4px] text-[14px] text-[#9F9F9F] flex items-center">
                                            <span class="mb-[2px] text-[13px]">{{ convertTime(comment.created_at) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div class="text-center my-[32px] text-[15px]">Không có bình luận nào</div>
                            </template>
                        </div>
                        <div v-if="nextComment" class="mx-auto btn w-[200px] mt-[28px] mx-[8px] border-[1px] border-[#adb5bd] text-center py-[8px] 
                            cursor-pointer text-[15px] active:bg-[#eff1f3]" @click="loadComment">
                            Xem thêm bình luận
                        </div>
                    </div>
                    <div class="mt-[12px] border-t-[2px]">
                        <div class="font-bold text-[17px] my-[12px]">Tạo bình luận của bạn</div>
                        <el-input
                            v-model="contentComment"
                            type="textarea"
                            :autosize="{ minRows: 3, maxRows: 4 }" 
                            placeholder="Nhập bình luận của bạn"
                        />
                        <div class="flex mt-[18px] mr-[18px]">
                            <div class="btn border-[1px] px-[16px] py-[8px] bg-[#9f224e] text-white text-[14px] cursor-pointer 
                                rounded-[4px] active:scale-95 ml-[12px]"
                                @click="sendComment">
                                Tạo bình luận
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main border-[1px] box-shadow">
                <div class="py-[12px] pt-[12px]">
                    <div class="heading mt-4px uppercase font-bold text-[#9f224e]]">
                        Bài viết liên quan
                    </div>
                    <div class="mt-[18px] grid grid-cols-3 gap-3 mb-[18px]">
                        <template v-for="post in listPostRelates">
                            <div class="mt-[8px]">
                                <Link :href="route('post', post.slug)">
                                    <img :src="post.image" class="h-[200px] object-cover w-[100%] post-image">
                                </Link>
                                <div class="px-[2px]">
                                    <Link :href="route('post', post.slug)">
                                        <div class="font-bold text-[15px] mt-[8px]">{{ post.title }}</div>
                                    </Link>
                                    <div class="flex items-center mt-[4px]">
                                        <Link :href="route('list-category', post.categorySlug)">
                                            <div class="text-[14px] font-bold text-[#076db6]">{{ post.categoryName }}</div>
                                        </Link>
                                        <div class="ml-[16px] text-[13px] mt-[4px]">{{ convertTime(post.created_at) }}</div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div v-if="isLoadPost" class="mx-auto btn w-[200px] mt-[28px] mx-[8px] border-[1px] border-[#adb5bd] py-[8px] 
                        flex justify-center cursor-pointer text-[15px] active:bg-[#eff1f3]" @click="loadPost">
                        Xem thêm bài viết
                    </div>
                </div>
            </div>

            <LoginForm ref="loginForm" @login="login"/>
            <ShowFormReport ref="showFormReport"/>
            <EditFormComment ref="editFormComment" @change-update="fetchDataComment"/>
            <ShowFormReportUser ref="showFormReportUser" @change-update="fetchDataComment"/>
        </template>
    </AppLayout>
</template>
<script>
import AppLayout from '../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3'
import moment from "moment";
import { ElMessage, ElMessageBox } from 'element-plus'
import LoginForm from '../Components/Auth/Login.vue';
import ShowFormReport from '../Components/Post/ShowFormReport.vue';
import ShowFormReportUser from '../Components/Post/ShowFormReportUser.vue';
import EditFormComment from '../Components/Post/EditFormComment.vue';
import axios from 'axios';

export default{
    components:{
        AppLayout,
        Link,
        LoginForm,  
        ShowFormReport,
        EditFormComment,
        ShowFormReportUser
    },
    data() {
        return {
            filters: {
                tabComment: 'tab-0',
                page: 0,
            },
            dataForm: {
                id: '',
                title: '',
                slug: '',
                title: '',
                description: '',
                image: '',
                categorySlug: '',
                categoryName: '',
                is_save: false,
                creator: '',
                creatorCode: '',
                created_at: '',
                updated_at: '',
                hagtags: [],
                hagtagNames: [],
            },
            listPostRelates: [],
            contentComment: '',
            comments: [],
            comment: {},
            pageComment: 6,
            totalcomment: 0,
            nextComment: false,
            isLoadPost: true,
            number_data: 6,
            errors: []
        }  
    },
    created() {
        this.fetchData()
        document.title = this.$page.props.title
    },
    methods: {
        moment,
        async loadPost() {
            const responseRelates = await axios.get(route('post.get-list-post-relate', 
                {'id': this.dataForm.id, number_data: this.number_data}))

            this.listPostRelates = responseRelates.data.data
            if(responseRelates.data.meta.current_page === responseRelates.data.meta.last_page) {
                this.isLoadPost = false
            }
            else{
                this.number_data += 6
            }
        },
        async fetchData() {
            const response = await axios.get(route('post.get-post', this.$page.props.slug))
            this.dataForm = response.data.data
            this.fetchDataComment()
            this.loadPost()
        },
        async fetchDataComment() {
            const responseComment = await axios.get(route('get-comments', {
                id: this.dataForm.id,
                page_number: this.pageComment,
                comment_id: this.comment ? this.comment.id : '',
            }))
            this.comments = responseComment.data.data
            this.totalcomment = responseComment.data.meta.total
            if(responseComment.data.meta.last_page == responseComment.data.meta.current_page) {
                this.nextComment = false
            }
            else{
                this.nextComment = true
            }
        },
        convertTime(created_at) {
            const now = moment()
            const time = moment(created_at)

            if( now.diff(time, 'years') > 0 ) {
                return `${now.diff(time, 'years')} năm trước`
            }
            else if(now.diff(time, 'months') > 0) {
                return `${now.diff(time, 'months')} tháng trước`
            }
            else if(now.diff(time, 'days') > 0) {
                return `${now.diff(time, 'days')} ngày trước`
            }
            else if(now.diff(time, 'hours') > 0) {
                return `${now.diff(time, 'hours')} giờ trước`
            }
            else if(now.diff(time, 'minutes') > 0) {
                return `${now.diff(time, 'minutes')} phút trước`
            }
            else if(now.diff(time, 'seconds') > 0) {
                return `${now.diff(time, 'seconds')} giây trước`
            }
        },
        sendComment() {
            if(this.$page.props.auth.account) {
                const pagram = {
                    ...{
                        'content': this.contentComment
                    }
                }
                axios.post(route('create-comment', this.dataForm.id), pagram)
                    .then(response => {
                        ElMessage({
                            showClose: true,
                            message: 'Thêm bình luận thành công',
                            type: 'success',
                        })
                        this.contentComment = ''
                        this.fetchDataComment()
                    })
                    .catch(errors => {
                        this.errors = errors.response.data.errors
                    })
            }
            else {
                this.$refs.loginForm.open()
            }
        },
        loadComment() {
            this.pageComment += 2
            this.fetchDataComment()
        },
        login() {
            location.reload()
        },

    }
}

</script>
<style scoped>
.comment-hidden .comment-item{
    display: none;
}
.comment-content-cut {
    display: none;
}
.comment-content-all{
    display: contents;
}
.comment-load-more .comment-content-cut {
    display: contents;
}
.comment-load-more .comment-content-all {
    display: none
}
.comment-child-content-cut {
    display: none;
}
.comment-child-content-all{
    display: contents;
}
.comment-child-load-more .comment-child-content-cut {
    display: contents;
}
.comment-child-load-more .comment-child-content-all {
    display: none
}
main .main {
    background-color: #fff;
    padding: 12px 12px 48px 12px;
    border-radius: 5px;
}
.active-comment {
    color: #db562b;
    padding-bottom: 12px;
    border-bottom: 2px solid #db562b;
}
main .main .comment {
    border-top: 1px solid #bdc1c5;
}
main .main .heading {
    margin-top: 4px;
    text-transform: uppercase;
    color: #9f224e;
    border-left: 4px solid #9f224e;
    padding-left: 12px;
    font-size: 0.96rem;
}
.comment-form {
    display: none;
}
.comment-active .comment-form {
    display: block;
}
</style>