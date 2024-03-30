<?php include('component\navbar.html'); ?>

<link rel="stylesheet" href="css/calculate.css">
<!--==================== CALCULATE ====================-->
<section class="calculate section" style="
    padding-bottom: 112px;
">
    <div class="calculate__container container grid">
        <div class="calculate__content">
            <div class="section__titles">
                <h1 class="section__title-border">CALCULATE</h1>
                <h1 class="section__title">YOUR BMI</h1>
            </div>

            <p class="calculate__description">
                The body mass index (BMI) calculator calculates
                body mass index from your weight and height.
            </p>

            <form action="" class="calculate__form" id="calculate-form">
                <div class="calculate__box">
                    <input type="number" placeholder="Height" class="calculate__input" id="calculate-cm">
                    <label for="" class="calculate__label">cm</label>
                </div>
                <div class="calculate__box">
                    <input type="number" placeholder="Weight" class="calculate__input" id="calculate-kg">
                    <label for="" class="calculate__label">kg</label>
                </div>

                <button type="submit" class="button button__flex">
                    Calculate Now <i class="ri-arrow-right-line"></i>
                </button>
            </form>

            <p class="calculate__message" id="calculate-message"></p>
        </div>

        <img src="assets/YOGASTRONG_AMO06_040_073_V03_ORANGE_300DPI_FINAL-lpr.webp" alt="calculate image" class="calculate__img">
    </div>
</section>
</main>
<?php include('component/footers.html');?>

              
<script>
    const calculateForm = document.getElementById('calculate-form'),
        calculateCm = document.getElementById('calculate-cm'),
        calculateKg = document.getElementById('calculate-kg'),
        calculateMessage = document.getElementById('calculate-message')

    const calculateBmi = (e) => {
        e.preventDefault()

        // Check if the fields have a value
        if (calculateCm.value === '' || calculateKg.value === '') {
            // Add and remove color
            calculateMessage.classList.remove('color-green')
            calculateMessage.classList.add('color-red')

            // Show message
            calculateMessage.textContent = 'Fill in the Height and Weight 👨‍💻'

            // Remove message three seconds
            setTimeout(() => {
                calculateMessage.textContent = ''
            }, 3000)
        } else {
            // BMI Formula
            const cm = calculateCm.value / 100,
                kg = calculateKg.value,
                bmi = Math.round(kg / (cm * cm))

            let message = ''
            // Show your health status
            if (bmi < 18.5) {
                message = `Your BMI is ${bmi} and you are skinny 😔`
            } else if (bmi < 25) {
                message = `Your BMI is ${bmi} and you are healthy 🥳`
            } else {
                message = `Your BMI is ${bmi} and you are overweight 😔`
            }

            // To clear the input field
            calculateCm.value = ''
            calculateKg.value = ''

            // Display result in alert
            alert(message)
        }
    }

    calculateForm.addEventListener('submit', calculateBmi)
</script>
