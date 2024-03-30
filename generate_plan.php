<!DOCTYPE html>
<html>
<head>
    <title>Generated Workout & Diet Plan</title>
</head>
<body>
<link rel="stylesheet" type="text/css" href="/css/gplan.css">
    <h1>Generated Workout & Diet Plan</h1>

    <?php
    // Function to calculate Basal Metabolic Rate (BMR) based on gender, age, weight, and activity level
    function calculateBMR($gender, $age, $weight, $activity) {
        if ($gender === "male") {
            $bmr = 88.362 + (13.397 * $weight) + (4.799 * $age) - (5.677 * $activity);
        } elseif ($gender === "female") {
            $bmr = 447.593 + (9.247 * $weight) + (3.098 * $age) - (4.330 * $activity);
        }
        return $bmr;
    }

    // Get form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $weight = $_POST['weight'];
    $goal = $_POST['goal'];
    $activity = $_POST['activity'];
    $duration = $_POST['duration'];
    $dietPreference = $_POST['diet_preference'];

    // Convert activity level to a numerical value for BMR calculation
    $activityLevel = 1.2; // Sedentary (little to no exercise)
    if ($activity === "light") {
        $activityLevel = 1.375; // Lightly Active (light exercise/sports 1-3 days/week)
    } elseif ($activity === "moderate") {
        $activityLevel  = 1.55; // Moderately Active (moderate exercise/sports 3-5 days/week)
    } elseif ($activity === "active") {
        $activityLevel = 1.725; // Very Active (hard exercise/sports 6-7 days/week)
    } elseif ($activity === "extra_active") {
        $activityLevel = 1.9; // Extra Active (very hard exercise & physical job or 2x training)
    }

    // Calculate BMR and adjust based on goal
    $bmr = calculateBMR($gender, $age, $weight, $activityLevel);
    if ($goal === "weight_gain") {
        $calories = $bmr + 500; // Add 500 calories for weight gain
    } elseif ($goal === "weight_loss") {
        $calories = $bmr - 500; // Subtract 500 calories for weight loss
    } else {
        $calories = $bmr; // Maintain weight
    }

    // Generate workout plan based on goal and duration
    $workoutPlan = generateWorkout($goal, $duration);

    // Generate diet plan based on calculated calories and diet preference
    $dietPlan = generateDiet($calories, $dietPreference);

    // Display the generated plans
    echo "<h2>Hi $name!</h2>";
    echo "<h3>Your Workout Plan:</h3>";
    echo "<p>$workoutPlan</p>";

    echo "<h3>Your Diet Plan:</h3>";
    echo "<p>$dietPlan</p>";

    // Function to generate workout plan
    function generateWorkout($goal, $duration) {
        $workoutPlan = "Your customized workout plan for $duration minutes:<br><br>";

        if ($goal === "weight_gain") {
            $workoutPlan .= "1. Warm-up: 10 minutes of light cardio<br>";
            $workoutPlan .= "2. Strength training:<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Bench press: 3 sets of 10 reps<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Squats: 3 sets of 12 reps<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Deadlifts: 3 sets of 8 reps<br>";
            $workoutPlan .= "3. Cardio: 20 minutes of jogging<br>";
        } elseif ($goal === "weight_loss") {
            $workoutPlan .= "1. Warm-up: 10 minutes of light cardio<br>";
            $workoutPlan .= "2. HIIT (High-Intensity Interval Training):<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Jumping jacks: 30 seconds<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Mountain climbers: 30 seconds<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Burpees: 30 seconds<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Rest: 30 seconds<br>";
            $workoutPlan .= "3. Core workout:<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Plank: 3 sets of 30 seconds<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Bicycle crunches: 3 sets of 20 reps<br>";
        } else {
            $workoutPlan .= "1. Warm-up: 10 minutes of light cardio<br>";
            $workoutPlan .= "2. Full-body workout:<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Push-ups: 3 sets of 12 reps<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Lunges: 3 sets of 10 reps per leg<br>";
            $workoutPlan .= "&nbsp;&nbsp;- Leg raises: 3 sets of 15 reps<br>";
            $workoutPlan .= "3. Cardio: $duration minutes of brisk walking, jogging, or cycling<br>";
        }

        return $workoutPlan;
    }

    // Function to generate diet plan based on calculated calories and diet preference
    function generateDiet($calories, $dietPreference) {
        $dietPlan = "Your customized diet plan based on approximately $calories calories per day:<br><br>";

        // Adjust the plan based on calorie needs
        $proteinGrams = $calories * 0.3 / 4; // 30% of calories from protein
        $carbGrams = $calories * 0.5 / 4; // 50% of calories from carbs
        $fatGrams = $calories * 0.2 / 9; // 20% of calories from fats

        // Diet options for both vegetarian and non-vegetarian
        $vegetarianOptions = array(
            "1. Protein Sources:",
            "&nbsp;&nbsp;- Lentils",
            "&nbsp;&nbsp;- Chickpeas",
            "&nbsp;&nbsp;- Tofu",
            "2. Carb Sources:",
            "&nbsp;&nbsp;- Quinoa",
            "&nbsp;&nbsp;- Brown rice",
            "&nbsp;&nbsp;- Whole wheat pasta",
            "3. Vegetables:",
            "&nbsp;&nbsp;- Spinach",
            "&nbsp;&nbsp;- Broccoli",
            "&nbsp;&nbsp;- Bell peppers",
            "4. Fruits:",
            "&nbsp;&nbsp;- Apples",
            "&nbsp;&nbsp;- Bananas",
            "&nbsp;&nbsp;- Berries",
            "5. Healthy Fats:",
            "&nbsp;&nbsp;- Avocado",
            "&nbsp;&nbsp;- Nuts and seeds",
            "&nbsp;&nbsp;- Olive oil"
        );

        $nonVegetarianOptions = array(
            "1. Protein Sources:",
            "&nbsp;&nbsp;- Grilled chicken breast",
            "&nbsp;&nbsp;- Salmon fillet",
            "&nbsp;&nbsp;- Turkey breast",
            "2. Carb Sources:",
            "&nbsp;&nbsp;- Sweet potatoes",
            "&nbsp;&nbsp;- Quinoa",
            "&nbsp;&nbsp;- Whole grain bread",
            "3. Vegetables:",
            "&nbsp;&nbsp;- Asparagus",
            "&nbsp;&nbsp;- Brussels sprouts",
            "&nbsp;&nbsp;- Zucchini",
            "4. Fruits:",
            "&nbsp;&nbsp;- Oranges",
            "&nbsp;&nbsp;- Berries",
            "&nbsp;&nbsp;- Watermelon",
            "5. Healthy Fats:",
            "&nbsp;&nbsp;- Avocado",
            "&nbsp;&nbsp;- Nuts and seeds",
            "&nbsp;&nbsp;- Olive oil"
        );

        // Based on user's preference, use the appropriate diet options
        if ($dietPreference === "vegetarian") {
            foreach ($vegetarianOptions as $option) {
                $dietPlan .= $option . "<br>";
            }
        } elseif ($dietPreference === "non_vegetarian") {
            foreach ($nonVegetarianOptions as $option) {
                $dietPlan .= $option . "<br>";
            }
            // Include vegetarian options in non-vegetarian diet plan
            $dietPlan .= "<br><strong>Additional Vegetarian Options:</strong><br>";
            foreach ($vegetarianOptions as $option) {
                $dietPlan .= $option . "<br>";
            }
        }

        $dietPlan .= "<br><strong>Sample";
        $dietPlan .= "<br><strong>Sample Daily Meal Plan:</strong><br>";

        if ($dietPreference === "vegetarian") {
            $dietPlan .= "1. Breakfast:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Oatmeal with almond milk and berries<br>";
            $dietPlan .= "2. Morning Snack:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Greek yogurt with honey and nuts<br>";
            $dietPlan .= "3. Lunch:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Lentil soup with whole grain bread<br>";
            $dietPlan .= "4. Afternoon Snack:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Hummus with carrot and cucumber sticks<br>";
            $dietPlan .= "5. Dinner:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Tofu stir-fry with mixed vegetables and brown rice<br>";
            $dietPlan .= "6. Evening Snack (Optional):<br>";
            $dietPlan .= "&nbsp;&nbsp;- Fruit salad with a sprinkle of chia seeds<br>";
        } elseif ($dietPreference === "non_vegetarian") {
            $dietPlan .= "1. Breakfast:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Scrambled eggs with spinach and whole wheat toast<br>";
            $dietPlan .= "2. Morning Snack:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Cottage cheese with pineapple chunks<br>";
            $dietPlan .= "3. Lunch:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Grilled chicken salad with avocado and quinoa<br>";
            $dietPlan .= "4. Afternoon Snack:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Turkey slices with whole grain crackers<br>";
            $dietPlan .= "5. Dinner:<br>";
            $dietPlan .= "&nbsp;&nbsp;- Baked salmon with steamed asparagus and sweet potato<br>";
            $dietPlan .= "6. Evening Snack (Optional):<br>";
            $dietPlan .= "&nbsp;&nbsp;- Protein shake with banana and almond butter<br>";
        }

        return $dietPlan;
    }
    ?>
</body>
</html>

