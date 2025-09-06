import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.preprocessing import StandardScaler
from sklearn.pipeline import Pipeline
from sklearn.metrics import accuracy_score, classification_report
import joblib

# === Load dataset ===
df = pd.read_csv("C:/xampp/htdocs/SmartShiksha/SmartShiksha/Student_performance_data.csv")py

# Create TotalMarks (GPA × subjects × scaling)
num_subjects = 5
df["TotalMarks"] = df["GPA"] * num_subjects * 25

# Pass/Fail from GradeClass
df["PassFail"] = df["GradeClass"].apply(lambda x: 1 if x in [1, 2, 3] else 0)

# Features (only TotalMarks) & Target
X = df[["TotalMarks"]]
y = df["PassFail"]

# Model pipeline (scaling + logistic regression)
model = Pipeline([
    ("scaler", StandardScaler()),
    ("clf", LogisticRegression(max_iter=1000))
])

# Train/Test Split
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42, stratify=y
)

# Train Model
model.fit(X_train, y_train)

# Evaluate Model
y_pred = model.predict(X_test)
accuracy = accuracy_score(y_test, y_pred)

print("Model Accuracy:", accuracy)
print("\nClassification Report:\n", classification_report(y_test, y_pred))

# Save Model
joblib.dump(model, "student_totalmarks_model.pkl")
print("Model saved as student_totalmarks_model.pkl")
