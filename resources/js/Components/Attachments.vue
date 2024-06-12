<template>
    <div class="attachments">
        <a :href="atta.download_url" class="attachment" target="_blank" v-for="atta of modelValue">
            <span class="img">
                <img v-if="atta.thumb_url" :src="atta.thumb_url" :alt="atta.name"/>
                <img v-else src="/images/file_types/default.svg" :alt="atta.name"/>
            </span>
            <span class="name">{{ atta.name }}</span>
        </a>
    </div>
</template>

<script>

export default {
    props: {
        modelValue: {
            type: [Array],
            default: [],
        }
    },
    computed: {}
}

</script>

<style scoped lang="scss">
@import "resources/css/admin-vars";
.attachments{
    display: flex;
    flex-wrap: wrap;
    gap: 15px;

    .attachment {
        width: 95px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        gap: 5px;
        &:hover {
            text-decoration: none;
        }

        .img {
            display: flex;
            @media screen and (min-width: 700px){
                min-width: 40px;
                height: 95px;
            }
            @media screen and (max-width: 700px){
                min-width: 80px;
                height: 95px;
            }
            border: 1px solid $shadow-color;
            border-radius: 4px;
            background-color: white;
            color: #67C3F2;
            position: relative;
            text-decoration: none;
            font-size: 12px;

            img {
                display: block;
                margin: auto;
                @media screen and (min-width: 700px){
                    min-width: 65px;
                    height: 70px;
                }
                @media screen and (max-width: 700px){
                    min-width: 80px;
                    height: 95px;
                }
            }
        }



        .name {
            display: inline;
            text-align: center;
            font-size: 14px;
            color: #333333;
            line-height: 17px;
            text-overflow: ellipsis;
            overflow: hidden;
            max-lines: 2;
        }
    }
}

</style>
