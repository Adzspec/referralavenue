<template>
    <div class="faq-wrapper">
        <h2 class="text-4xl font-bold text-center pt-10">Faqs</h2>

        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question" @click="toggle($event)">
                    <span>Where is my order? Quisque molestie?</span>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer hidden">
                    Contrary to popular belief, Lorem Ipsum is not simply random text. It
                    has roots in a piece of classical Latin literature Id pro doctus
                    mediocrem erroribus, diam nostro sed cu. Ea pri graeco tritani
                    partiendo. Omittantur No tale choro fastidii his, pri cu epicuri
                    perpetua. Enim dictas omittantur et duo, vocent lucilius quaestio mea
                    ex. Ex illum officiis id.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" @click="toggle($event)">
                    <span>How can I return an item purchased online?</span>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer hidden">
                    Since 2007 we have been a visionary and a reliable software design
                    engineering partner for world-class brands. We are a boutique digital
                    transformation consultancy. Id pro doctus mediocrem erroribus, diam
                    nostro sed cu. Ea pri graeco tritani partiendo. Omittantur No tale
                    choro fastidii his, pri cu epicuri perpetua. Enim dictas omittantur et
                    duo, vocent lucilius quaestio mea ex. Ex illum officiis id.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" @click="toggle($event)">
                    <span>Can I cancel or change my order?</span>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer hidden">
                    Id pro doctus mediocrem erroribus, diam nostro sed cu. Ea pri graeco
                    tritani partiendo. Omittantur No tale choro fastidii his, pri cu
                    epicuri perpetua. Enim dictas omittantur et duo, vocent lucilius
                    quaestio mea ex. Ex illum officiis id. Contrary to popular belief,
                    Lorem Ipsum is not simply random text. It has roots in a piece of
                    classical Latin literature
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" @click="toggle($event)">
                    <span>I have promotional or discount code?</span>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer hidden">
                    Id pro doctus mediocrem erroribus, diam nostro sed cu. Ea pri graeco
                    tritani partiendo. Omittantur No tale choro fastidii his, pri cu
                    epicuri perpetua. Enim dictas omittantur et duo, vocent lucilius
                    quaestio mea ex. Ex illum officiis id. Contrary to popular belief,
                    Lorem Ipsum is not simply random text. It has roots in a piece of
                    classical Latin literature
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" @click="toggle($event)">
                    <span>What are the delivery types you use?</span>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer hidden">
                    We use standard, express, and next-day delivery options. You can
                    select the preferred method during checkout.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" @click="toggle($event)">
                    <span>How can I pay for my purchases?</span>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer hidden">
                    You can pay using credit/debit cards, PayPal, Apple Pay, and other
                    secure payment gateways.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" @click="toggle($event)">
                    <span>Can I cancel my order?</span>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer hidden">
                    Orders can be cancelled before they are processed for shipping. Once
                    shipped, cancellation is not possible.
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Question",
    methods: {
        toggle(event) {
            const question = event.currentTarget;
            const faqItem = question.parentElement;
            const answer = faqItem.querySelector(".faq-answer");
            const icon = question.querySelector(".toggle-icon");

            const isHidden = answer.classList.contains("hidden");

            // Close all other answers
            document.querySelectorAll(".faq-answer").forEach((el) => {
                el.classList.add("hidden");
            });
            document.querySelectorAll(".toggle-icon").forEach((el) => {
                el.textContent = "+";
            });

            // Open selected if it was hidden
            if (isHidden) {
                answer.classList.remove("hidden");
                icon.textContent = "−";
            }
        },
    },
};
</script>

<style scoped>
.faq-wrapper {
    max-width: 700px;
    margin: 0 auto;
    padding: 1rem;
}

.faq-item {
    margin-bottom: 1rem;
    cursor: pointer;
    padding: 1rem 0;
    border-bottom: 1px solid #e0e0e0;
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
    font-size: 1.1rem;
}

.faq-answer {
    margin-top: 0.5rem;
    color: #333;
    line-height: 1.5;
    font-size: 0.95rem;
}

.hidden {
    display: none;
}

.toggle-icon {
    font-size: 1.2rem;
    font-weight: bold;
}
</style>
