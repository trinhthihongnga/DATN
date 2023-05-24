<template>
    <header class="fixed top-0 left-0 right-0 h-[56px] leading-[56px] bg-[#fff] border-b-[2px] border-b-[#ced4da]">
        <div class="mr-[18px] flex justify-between">
            <div>
                <div class="flex justify-between items-center text-[16px] mx-[12px]">
                    <div class="text-[28px] cursor-pointer ml-[3px]" @click="triggerMenu">
                        <i class="bi bi-list"></i>
                    </div>
                    <span class="text-[17px] ml-[16px]">Trang chủ</span>
                </div>
            </div>
            <div class="flex items-center" id="tab-option">
                <Dropdown align="right" class="text-[#000]" :contentClasses="[backgroundColor]">
                    <template #trigger>
                        <div class="flex items-center">
                            <span class="text-[13px] mr-[4px]">{{ this.$page.props.auth.user.name }}</span>
                            <div class="w-[28px] h-[28px] bg-[#9f224e] text-white rounded-[50%] leading-[28px] text-center text-[14px] cursor-pointer">
                                {{ this.$page.props.auth.user.name[0] }}
                            </div>
                        </div>
                    </template>
                    <template #content>
                        <div class="py-[8px]">
                            <div class="text-[14px] h-[36px] flex items-center pl-[18px] cursor-pointer hover:bg-[#dee2e6]"
                                @click="logout">
                                <span class="text-[16px] text-[#9f224e]"><i class="bi bi-box-arrow-right"></i></span>
                                <span class="ml-[8px]">Đăng xuất</span>
                            </div>
                        </div>
                    </template>
                </Dropdown>
            </div>
        </div>
    </header>
    <main>
        <div id="slide" class="fixed top-[56px] bottom-0 w-[236px] border-r-[2px] bg-[#9f224e] text-white pt-[12px]
            pb-[58px] max-h-[100%]"
         :class="{ 'slide-menu' : !isOpenMenu }">
            <div class="w-[100%]">                    
                <div v-for="menu in menus" class="text-[15px]" :id="menu.tab">
                    <Link :href="route(menu.route)">
                        <div class="px-[24px] py-[10px] hover:bg-[#ab5d78] flex items-center
                        cursor-pointer"
                        :class="{ 'bg-[#ab5d78]' : menu.tab == currentTab }">
                            <div v-html="menu.icon" class="text-[18px] w-[24px]"></div>
                            <div class="mx-[8px]">{{ menu.name }}</div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
        <div class="mt-[57px] min-h-[630px] py-[12px] px-[24px] bg-[#e9ecef]" :class="{ 'px-[28px]' : !isOpenMenu, 'ml-[230px]' : isOpenMenu}">
            <slot name="main-full"/>
        </div>
    </main>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { ElMessage, ElMessageBox } from 'element-plus'
import Dropdown from "@/Components/Dropdown.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    components: {
        Link,
        Dropdown,
    },
    props: {
        title: String,
        currentTab: { type: String, required: true },
        currentTabChild: { type: String, required: true },
        backgroundColor: {
            type: String,
            default: "bg-[#fff]",
        },
    },
    data() {
        return {
            menus: [
                {
                    icon: '<i class="bi bi-graph-up"></i>',
                    name: 'Thống kê',
                    route: 'admin.dashboard',
                    tab: 'tab-0'
                },
                {
                    icon: '<i class="bi bi-person"></i>',
                    name: 'Quản lý người dùng',
                    route: 'admin.list-account',
                    tab: 'tab-1'
                },
                {
                    icon: '<i class="bi bi-postcard"></i>',
                    name: 'Quản lý chủ đề',
                    route: 'admin.list-category',
                    tab: 'tab-2'
                },
                {
                    icon: '<i class="bi bi-book"></i>',
                    name: 'Quản lý bài báo',
                    route: 'admin.list-post',
                    tab: 'tab-4'
                },
            ],
            isOpenMenu: true,
        }
    },
    created() {
    },
    methods: {
        triggerMenu() {
            this.isOpenMenu = !this.isOpenMenu
        },
        logout() {
            Inertia.get(route("admin.logout"));
        },
        openMenuChild(menu) {
            document.getElementById(menu).querySelector('.menu').classList.toggle('menu-active')
        }
    }
}
</script>

<style>
#tab-option .absolute {
    top: 82%;
}
.slide-menu {
    display: none;
    transition: linear all 1s;
}
header {
    z-index: 1000;
}
header,
#slide{
    -moz-user-select: none !important;
    -webkit-touch-callout: none!important;
    -webkit-user-select: none!important;
    -khtml-user-select: none!important;
    -moz-user-select: none!important;
    -ms-user-select: none!important;
    user-select: none!important;
}

#slide::-webkit-scrollbar {
    width: 10px;
}

/* Track */
#slide::-webkit-scrollbar-track {
    background: #fff;
}

/* Handle */
#slide::-webkit-scrollbar-thumb {
    background: #ced4da;
    border-radius: 10px;
}

/* .menu:hover .menu-childs, */
.menu-active .menu-childs {
    display: block;
    transition: all 3s linear;
}
.menu-childs {
    display: none;
}
.bi-chevron-down {
    display: none;
}
.bi-chevron-up {
    display: contents;
}
/* .menu:hover .bi-chevron-up, */
.menu-active .bi-chevron-up {
    display: none;
}
/* .menu:hover .bi-chevron-down, */
.menu-active .bi-chevron-down {
    display: contents;
}
</style>
