from flask import Flask, request, jsonify
from flask_cors import CORS

import mysql.connector

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}}, supports_credentials=True)
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'qlysukien'
}

def update_event_count(event_id, action):
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()

    # Tạo sự kiện nếu chưa có
    cursor.execute("INSERT IGNORE INTO sukien (MaSK) VALUES (%s)", (event_id,))

    # Cập nhật lượt tương tác
    if action == "click":
        cursor.execute("UPDATE sukien SET luot_truycap = luot_truycap + 1 WHERE MaSK = %s", (event_id,))
    elif action == "search":
        cursor.execute("UPDATE sukien SET luot_timkiem = luot_timkiem + 1 WHERE MaSK = %s", (event_id,))

    conn.commit()
    cursor.close()
    conn.close()

@app.route("/track", methods=["POST"])
def track_event():
    data = request.json
    print("Received:", data)  # Kiểm tra log
    event_id = data.get("MaSK")
    action = data.get("action")  # Các hoạt động: "click", "search"

    if not all([event_id, action]):
        return jsonify({"error": "Missing fields"}), 400

    update_event_count(event_id, action)
    return jsonify({"status": "updated"}), 200

if __name__ == "__main__":
    app.run(debug=True)
