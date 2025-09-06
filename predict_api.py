from flask import Flask, request, jsonify
import joblib
import pandas as pd

app = Flask(__name__)
model = joblib.load("student_totalmarks_model.pkl")

@app.route("/predict", methods=["POST"])
def predict():
    data = request.json   # expects { "TotalMarks": 350 }
    df = pd.DataFrame([data])
    
    pred = model.predict(df)[0]
    result = "Pass" if pred == 1 else "Fail"
    return jsonify({"prediction": result})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
