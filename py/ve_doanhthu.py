from flask import Flask, jsonify, request
import mysql.connector

app = Flask(__name__)

@app.route("/chart-data")
def chart_data():
    event_id = request.args.get("event_id")  # lấy tham số từ URL
    days = int(request.args.get("days", 10)) # mặc định 10 ngày

    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="qlysukien"
    )
    cursor = conn.cursor()

    sql = """
        SELECT DATE(NgayTao) AS Ngay,
               SUM(SoTien) AS TongDoanhThu,
               COUNT(v.MaVe) AS TongVe
        FROM ThanhToan tt
        LEFT JOIN ve v ON tt.MaTT = v.MaTT
        LEFT JOIN loaive lv ON v.MaLoai = lv.MaLoai
        LEFT JOIN sukien sk ON lv.MaSK = sk.MaSK
        WHERE NgayTao >= CURDATE() - INTERVAL %s DAY
    """
    params = [days]

    if event_id:
        sql += " AND sk.MaSK = %s"
        params.append(event_id)

    sql += " GROUP BY DATE(NgayTao) ORDER BY Ngay ASC"

    cursor.execute(sql, params)
    rows = cursor.fetchall()
    conn.close()

    labels = [str(r[0]) for r in rows]
    doanh_thu = [int(r[1]) for r in rows]
    ve_ban = [int(r[2]) for r in rows]

    return jsonify({
        "labels": labels,
        "datasets": [
            {"label": "Doanh thu", "data": doanh_thu, "borderColor": "#2980b9", "fill": False},
            {"label": "Vé bán", "data": ve_ban, "borderColor": "#27ae60", "fill": False}
        ]
    })
