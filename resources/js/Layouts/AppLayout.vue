<template>
    <header class="fixed top-0 left-0 right-0 z-1000 box-shadow">
        <div class="header-search">
            <div class="flex items-center h-[62px] bg-[#ffffff] text-white">
                <div class="mx-auto max-w-[920px] w-[920px]">
                    <div class="row">
                        <div class="col-4 flex items-center text-black">
                            <Link :href="route('home')">
                                <img src="/image/logo/logo.png" alt="Logo bao moi" class="h-[60px]">
                            </Link>
                            <div class="border-l-[2px] pl-[12px] text-[14px]">
                                {{ this.getDay() }}
                            </div>
                        </div>
                        <div class="col-8 mt-1 flex items-center search">
                            <el-input v-model="valueSearch.search" placeholder="Nhập nội dung tìm kiếm" clearable
                                        @keyup.enter="openPageSearch()"/>
                            <div v-if="!this.$page.props.auth.account" class="w-[390px] ml-[18px] cursor-pointer text-black">
                                <div class="flex text-[14px]">
                                    <div class="flex items-center" @click="openLogin">
                                        <div class="w-[22px]">
                                            <i class="bi bi-person-check-fill text-[19px]"></i>
                                        </div>
                                        <span class="ml-[3px]">Đăng nhập</span>
                                    </div>
                                    <div class="ml-[12px] flex items-center" @click="openRegister">
                                        <div class="w-[22px]">
                                            <i class="bi bi-person-circle text-[19px]"></i>
                                        </div>
                                        <span class="ml-[5px] min-w-[100px]">Đăng ký</span>
                                    </div>
                                </div>
                            </div>
                            <el-dropdown v-else ref="dropdown1" trigger="contextmenu">
                                <span class="w-[190px] ml-[18px] el-dropdown-link" @click="clickDropdown()">
                                    <div class="flex items-center text-[13px]">
                                        <div v-if="!this.$page.props.auth.account.image" class="bg-[#9f224e] w-[23px] h-[23px] border-[1px] rounded-[50%]
                                                flex justify-center items-center">
                                            <span class="text-[#fff] text-[10px]">{{
                                                this.$page.props.auth.account.first_name[0] }}</span>
                                        </div>
                                        <img v-else :src="this.$page.props.auth.account.image" alt=""
                                            class="w-[23px] h-[23px] rounded-[50%]">
                                        <span class="ml-[4px]">
                                            {{ this.$page.props.auth.account.first_name + ' ' +
                                                this.$page.props.auth.account.last_name }}
                                        </span>
                                    </div>
                                </span>
                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item @click="redirctPage('user.my-profile')">
                                            <div class="w-[23px]"><i class="bi bi-card-list text-[16px"></i></div>
                                            <span class="ml-[3px]">Quản lý thông tin</span>
                                        </el-dropdown-item>
                                        <el-dropdown-item @click="redirctPage('user.my-post')">
                                            <div class="w-[23px]"><i class="bi bi-file-earmark-post"></i></div>
                                            <span class="ml-[3px]">Quản lý bài viết</span>
                                        </el-dropdown-item>
                                        <el-dropdown-item @click="openChangePassword">
                                            <div class="w-[23px]"><i class="bi bi-key text-[17px]"></i></div>
                                            <span class="ml-[3px]">Thay đổi mật khẩu</span>
                                        </el-dropdown-item>
                                        <el-dropdown-item @click="logout">
                                            <div class="w-[23px]"><i class="bi bi-box-arrow-in-right text-[17px]"></i>
                                            </div>
                                            <span class="ml-[3px]">Đăng xuất</span>
                                        </el-dropdown-item>
                                    </el-dropdown-menu>
                                </template>
                            </el-dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-main bg-[#fff] border-[2px]">
            <div class="flex items-center font-bold">
                <div class="mx-auto flex justify-between items-center py-[2px]">
                    <div class="flex items-center">
                        <Link :href="route('home')">
                            <div class="main-item hover:bg-[#eaeeef]">
                                Trang chủ
                            </div>
                        </Link>
                        <template v-for="(item, index) in this.$page.props.categories">
                            <Link :href="route('list-category', item.slug)" v-if="index < 11">
                                <div class="main-item hover:bg-[#eaeeef] text-[14px]">
                                    {{ item.name }}
                                </div>
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="mx-auto pt-[118px] pb-[12px]"
         :class="{ 'w-[1070px]' : currentTab != 'main-full', 'w-[870px]' : currentTab == 'main-full' }">
            <div v-if="currentTab == 'main-full'" class="main flex justify-center">
                <slot name="main-full" />
            </div>
            <div v-else class="flex">
                <div v-if="currentTab == 'main-creater'" class="w-[310px]">
                    <slot name="main-2" />
                </div>
                <div v-else class="w-[300px]">
                    <slot name="nav-1" />
                    <div class="bg-[#fff] py-[12px] mb-[24px] rounded-[4px] box-shadow border-[1px]">
                        <div class="border-b-[2px] border-[#9f224e] mt-[4px] mb-[8px]">
                            <div
                                class="border-l-[5px] border-[#9f224e] text-[#9f224e] font-bold ml-[8px] pl-[8px] mb-[12px] text-[17px]">
                                Tin mới nhất
                            </div>
                        </div>
                        <div v-for="post in listNewPost" class="post-item flex border-b-[2px] mx-[8px] py-[8px] hover:bg-[#e9ecef]">
                            <Link :href="route('post', post.slug)">
                                <img :src="post.image" alt="" class="post-image w-[60px] min-w-[60px] h-[60px] rounded-[3px] object-cover">
                            </Link>
                            <div class="ml-[8px]">
                                <Link :href="route('post', post.slug)">
                                    <div class="post-new-title text-[15px] mr-[8px]"> {{ post.title }} </div>
                                </Link>
                                <div class="flex items-center mt-[6px]">
                                    <Link :href="route('list-category', post.categorySlug)">
                                        <div class="text-[#076db6] font-bold text-[13px]"> {{ post.categoryName }}</div>
                                    </Link>
                                    <div class="text-[12px] ml-[13px]">{{ this.convertTime(post.created_at) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#fff] py-[12px] mt-[52px] rounded-[4px] box-shadow border-[1px]">
                        <div class="border-b-[2px] border-[#9f224e] mt-[4px] mb-[8px]">
                            <div
                                class="border-l-[5px] border-[#9f224e] text-[#9f224e] font-bold ml-[8px] pl-[8px] mb-[12px] text-[17px]">
                                Bài viết quan tâm
                            </div>
                        </div>
                        <template v-for="post in listPostInterested">
                            <div class="post-item flex border-b-[2px] mx-[8px] py-[8px] hover:bg-[#e9ecef]">
                                <Link :href="route('post', post.slug)">
                                    <img :src="post.image" alt="" class="post-image w-[60px] min-w-[60px] h-[60px] rounded-[3px] object-cover">
                                </Link>
                                <div class="ml-[8px]">
                                    <Link :href="route('post', post.slug)">
                                        <div class="post-new-title text-[15px] mr-[8px]"> {{ post.title }} </div>
                                    </Link>
                                    <div class="flex items-center mt-[6px]">
                                        <Link :href="route('list-category', post.categorySlug)">
                                            <div class="text-[#076db6] font-bold text-[13px]"> {{ post.categoryName }}</div>
                                        </Link>
                                        <div class="text-[12px] ml-[13px]">{{ this.convertTime(post.created_at) }}</div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex-1 pl-[18px]">
                    <slot name="main-1"/>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-[#fff] box-shadow-bottom">
        <div class="border-t-2 py-3 pb-[58px]">
            <div class="max-w-[970px] mx-auto flex justify-between text-[15px]">
                <div class="flex-1 pr-[12px]">
                    <div><b>Báo dành cho người Việt</b></div>
                    <div>Đánh giá rất hay trên các nền tảng</div>
                    <div>Tổng biên tập: Trịnh Thị Hồng Nga</div>
                    <div>Địa chỉ: Thái Bình, Hà Nội </div>
                    <div>Số điện thoại: 0358387102 </div>
                </div>
                <div class="flex-1 text-right">
                    <div>
                        © 1997-2023. Toàn bộ bản quyền thuộc quyền sở hữu của lập trình viên
                        <Link :href="route('home')" class="font-bold hover:underline">Trịnh Thị Hồng Nga</Link>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <LoginForm ref="loginForm" @login="login"></LoginForm>
    <RegisterForm ref="registerForm" @register="register"></RegisterForm>
    <ChangePasswordForm ref="changePasswordForm" @change-password="changePassword"></ChangePasswordForm>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { ElMessage, ElMessageBox } from 'element-plus'
import Dropdown from "@/Components/Dropdown.vue";
import LoginForm from '../Components/Auth/Login.vue';
import RegisterForm from '../Components/Auth/Register.vue';
import ChangePasswordForm from '../Components/Auth/ChangePassword.vue';
import { Search } from '@element-plus/icons-vue'
import moment from "moment";
import axios from 'axios';

export default {
    components: {
        Link,
        Dropdown,
        LoginForm,
        RegisterForm,
        ChangePasswordForm,
        Search
    },
    props: {
        title: String,
        currentTab: { type: String, required: true },
        backgroundColor: {
            type: String,
            default: "bg-[#fff]",
        },
    },
    data() {
        return {
            input: "",
            hiddenMenu: false,
            isSearchData: false,
            valueSearch: {
                search: ''
            },
            dataSearch: [],
            options: [],
            listHagTag: [],
            listNewPost: [],
            listPostInterested: [],
            listNotice: [],
            countNotice: 0,
            filterNotice: {
                limit: 5
            },
            nextNotice: true,
        }
    },
    mounted() {
    },
    created() {
        this.fetchData()
    },
    methods: {
        moment,
        cleareDataSearch() {
            if(!this.valueSearch.search) {
                this.dataSearch = []
            }
        },
        async fetchData() {
            this.listHagTag = this.$page.props.hagtags
            if (this.currentTab != 'main-full' && this.currentTab != 'main-creater') {
                const responseNewPost = await axios.get(route('post.get-new-all'))
                this.listNewPost = responseNewPost.data.data

                const responsePostInterested = await axios.get(route('post.get-post-interested'))
                this.listPostInterested = responsePostInterested.data.data
            }

        },
        nextMoreNotice() {
            this.filterNotice.limit += 4
            this.loadNotice()
        },
        getDay() {
            var date = new Date();
            var current_day = date.getDay();
            var day_name = '';
            // Lấy tên thứ của ngày hiện tại
            switch (current_day) {
                case 0:
                    day_name = "Chủ nhật";
                    break;
                case 1:
                    day_name = "Thứ hai";
                    break;
                case 2:
                    day_name = "Thứ ba";
                    break;
                case 3:
                    day_name = "Thứ tư";
                    break;
                case 4:
                    day_name = "Thứ năm";
                    break;
                case 5:
                    day_name = "Thứ sau";
                    break;
                case 6:
                    day_name = "Thứ bảy";
            }
            return day_name + ', ' + moment().format('DD/MM/YYYY')
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
        },
        clickDropdown() {
            this.$refs.dropdown1.handleOpen()
        },
        clickNotification() {
            this.$refs.dropdown2.handleOpen()
        },
        openLogin() {
            this.$refs.loginForm.open()
        },
        login() {
            location.reload()
        },
        openRegister() {
            this.$refs.registerForm.open()
        },
        register() {
            location.reload()
        },
        openChangePassword() {
            this.$refs.changePasswordForm.open()
        },
        showClick() {
            this.$refs.dropdown1.handleOpen()
        },
        redirctPage(url, tab) {
            this.$inertia.visit(route(url))
        },
        async logout() {
            ElMessageBox.confirm(
                'Bạn có muốn đăng xuất tài khoản khỏi hệ thống không?',
                'Đăng xuất',
                {
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy bỏ',
                }
            )
            .then(() => {
                axios.get(route('guest.logout'))
                    .then(response => {
                        ElMessage({
                            type: 'success',
                            message: 'Đăng xuất tài khoản thành công',
                        })
                        location.reload()
                    })
                    .catch(errors => {
                        ElMessage({
                            type: 'error',
                            message: errors.data,
                        })
                    })
            })
            .catch(() => {})
        },
        openPageSearch() {
            this.$inertia.visit(route('search', this.valueSearch.search))
        }
    }
}
</script>

<style>
#list-notice::-webkit-scrollbar {
    width: 8px !important;
}
#list-notice::-webkit-scrollbar-track {
    background: #fff !important;
}
#list-notice::-webkit-scrollbar-thumb {
    background: #ced4da !important;
    border-radius: 10px !important;
}
.header-title {
  max-height: 52px;
  line-height: 22px;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
}
.el-dropdown {
    border-color: white !important;
}
header {
    z-index: 100;
}
header .absolute {
    width: 100%;
}
header .header-extend {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    border-radius: 5px;
    -moz-user-select: none !important;
    -webkit-touch-callout: none !important;
    -webkit-user-select: none !important;
    -khtml-user-select: none !important;
    -moz-user-select: none !important;
    -ms-user-select: none !important;
    user-select: none !important;
}
header .main-item {
    padding: 9px 12px;
    border-left: 1px solid #ffffffc3;
    font-size: 0.82rem;
    text-transform: uppercase;
    cursor: pointer;
}
header .main-item a {
    height: 100%;
    width: 100%;
}
header .main-hagtag {
    padding: 1px 8px;
    border-radius: 20px;
    margin-left: 12px;
    border: 1px solid white;
    font-size: 0.82rem;
}
header .header-search .search {
    padding: 0 !important;
}
main .container-fuild {
    width: 1070px;
    margin: 0 auto;
    padding-top: 118px;
    padding-bottom: 30px;
}
/* main .container-fuild .main-1 {
    margin-right: 16px;
    flex: 2;
} */
.post-new-title {
    max-height: 48px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
}
</style>