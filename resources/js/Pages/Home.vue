<template>
    <AppLayout :currentTab="'home'" :title="'Trang chủ'">
        <template v-slot:main-1>
            <div class="p-[8px] border-[1px] box-shadow">
                <div class="row p-[8px]">
                    <template v-for="(post, index) in listPostPopular">
                        <div v-if="index < 2" class="col-6 pb-[18px] border-b-[2px]">
                            <Link :href="route('post', post.slug)">
                                <img class="post-image w-[100%] h-[280px] object-cover" :src="post.image" :alt="post.description">
                                <div class="post-title font-bold text-[21px] py-2">{{ post.title }}</div>
                            </Link>
                            <div class="flex items-end">
                                <Link :href="route('list-category', post.categorySlug)">
                                    <div class="text-[#076db6] text-[16px] font-bold">
                                        {{ post.categoryName }}
                                    </div>
                                </Link>
                                <div class="text-[15px] mx-[16px]">{{ convertTime(post.created_at) }}</div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="col-12 mx-[4px] flex py-[18px] border-b-[2px]">
                                <Link :href="route('post', post.slug)">
                                    <img :src="post.image" :alt="post.description" class="post-image w-[220px] min-w-[220px] h-[150px] object-cover">
                                </Link>
                                <div class="ml-[12px]">
                                    <Link :href="route('post', post.slug)">
                                        <div class="post-title text-[16px] font-bold">{{ post.title }}</div>
                                    </Link>
                                    <div class="mt-[12px] flex items-end">
                                        <Link :href="route('list-category', post.categorySlug)">
                                            <div class="text-[15px] font-bold text-[#076db6]">
                                                {{ post.categoryName }}
                                            </div>
                                        </Link>
                                        <div class="text-[13px] mx-[16px]">{{ convertTime(post.created_at) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </AppLayout>
</template>
<script>
import axios from 'axios';
import AppLayout from '../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3'
import moment  from 'moment';

export default{
    components:{
        AppLayout,
        Link,
    },
    data() {
        return {
            currentTab: 'tab-0',
            listPostPopular: [],
            listHagtagPopular: [],
            listPosts: []
        }  
    },
    created() {
        this.fecthData()
        this.listHagtagPopular = this.$page.props.hagtags
        document.title = "Trang chủ"
    },
    methods: {
        moment,
        async fecthData() {
            await axios.get(route('post.get-list-post-popular'))
                .then(response => {
                    this.listPostPopular = response.data.data
                })
                .catch(errors => {})
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
    },

}

</script>
<style scoped>
main .main {
    background-color: #fff;
    padding: 12px;
    box-shadow: 0 1px 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}
</style>
  