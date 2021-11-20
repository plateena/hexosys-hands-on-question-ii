import Vue from "vue";
import VueJsonPretty from "vue-json-pretty";
import "vue-json-pretty/lib/styles.css";

new Vue({
    el: "#upload-section",
    data: {
        imageUrl: "",
        imageBinary: "",
        result: {
            Version: "1.1 Beta",
            Message: "succeed",
            Code: 0,
            "Min-Confidence": "0.5",
            ModerationLabels: {
                Weapon: { Confidence: "1.0", Labels: { Missile: 1 } },
                Gambling: { Confidence: "0.98", Labels: { Gambling: 0.98 } },
            },
        },
        headers: {},
    },
    components: {
        VueJsonPretty,
    },
    mounted() {},
    methods: {
        processUrlForm(event) {
            this.postFile(event);
        },
        processFileChange(name, file) {
            var reader = new FileReader();
            reader.addEventListener("load", this.postFile);
            reader.readAsArrayBuffer(file);
        },
        async postFile(event) {
            // let url =
            //     "https://asia-east2-falcon-293005.cloudfunctions.net/falcon";
            let url = "/api/moderate";
            try {
                let response = await axios.post(
                    url,
                    this.populateFormData(event),
                    {
                        headers: this.headers,
                    }
                );

                this.result = response.data;

                console.log({ result: response });
            } catch (error) {
                /* handle error */
                console.log({ error: error });
            }
        },

        populateFormData(event) {
            if (event.constructor.name == "ProgressEvent") {
                this.headers = { "Content-Type": "image/jpeg" };
                return event.target.result;
            }

            this.headers = {
                "Content-Type": "application/x-www-form-urlencoded",
            };
            let formData = new FormData();
            formData.append("imageUrl", this.imageUrl);
            return formData;
        },
    },
});
