<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors[this.sendAs] }">
            <label :for="sendAs" class="control-label">Avatar</label>
            <div v-if="uploading">Processing</div>
            <input v-else type="file" v-on:change="fileChange" :name="sendAs">

            <div class="help-block" v-if="errors[this.sendAs]">
                {{ errors[this.sendAs][0] }}
            </div>
        </div>

        <div v-if="avatar.path">
            <input type="hidden" name="avatar_id" :value="avatar.id">
            <img class="avatar" :src="avatar.path" alt="Current avatar">
        </div>
    </div>
</template>

<script>
    import upload from '../mixins/upload'

    export default {
        props: [
            'currentAvatar'
        ],
        data () {
            return {
                errors: [],
                avatar: {
                    id: null,
                    path: this.currentAvatar
                }
            }
        },
        mixins: [
            upload
        ],
        methods: {
            fileChange (e) {
                this.upload(e).then((response) => {
                    this.avatar = response.data.data
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                        return
                    }

                    this.errors = 'Something went wrong. Try again.'
                })
            }
        }
    }
</script>
