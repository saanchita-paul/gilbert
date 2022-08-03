<template>
    <v-container>
        <v-row>
            <v-col>
                <div class="progress">
                    <div class="progress-item" v-for="item in steps">
                        <div class="label">
                            <span>{{ item.label }}</span>
                        </div>
                        <div class="title">
                            <span>{{ item.title }}</span>
                        </div>
                    </div>
                </div>
            </v-col>
        </v-row>

    </v-container>
</template>

<script>


export default {
    name: "AgentProgressStatus",
    data() {
        return {
            steps: [
                {label: '1', title: 'New'},
                {label: '2', title: 'Confirming'},
                {label: '3', title: 'Contact'},
                {label: '4', title: 'Test'},
                {label: '5', title: 'Boring'},
            ]
        }
    },
}
</script>

<style scoped>

.progress {
    list-style-type: none;
    display: flex;
    justify-content: center;
    padding: 0;
    text-align: center;
}

.progress-item {

}

.progress .progress-item:last-child .title {
    border-top: 0;
}

.progress .progress-item:last-child .title span::before {
    top: -12px !important;
    left: -1px !important;
}

.label {
    width: 200px;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
}

.title {
    display: flex;
    border-top: 4px solid #542E89;
    position: relative;
}

.title span {
    font-weight: 400;
    padding-top: 20px;
    color: #542E89;
}

.title span:before {
    content: '';
    width: 25px;
    height: 25px;
    background-color: #FFFFFF;
    border-radius: 25px;
    border: 4px solid #542E89;
    position: absolute;
    top: -15px;
}


</style>
