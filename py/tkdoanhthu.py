import pandas as pd
import matplotlib.pyplot as plt
import mysql.connector
import matplotlib.ticker as mtick
import textwrap

# Kết nối tới MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="qlysukien"
)

# Truy vấn dữ liệu
query = "SELECT s.TenSK, SUM(lv.Gia) as doanh_thu FROM sukien s JOIN loaive lv ON s.MaSK = lv.MaSK JOIN ve v ON lv.MaLoai = v.MaLoai WHERE v.TrangThai = 'Đã bán' GROUP BY s.MaSK ORDER BY doanh_thu"
df = pd.read_sql_query(query, conn)

# Ngắt dòng tên sự kiện nếu quá dài
df["TenSK"] = df["TenSK"].apply(lambda x: "\n".join(textwrap.wrap(x, width=25)))

# Vẽ biểu đồ cột ngang
plt.figure(figsize=(12, 10))
plt.barh(df["TenSK"],  df["doanh_thu"], color="skyblue")
plt.title("Doanh thu theo sự kiện (MySQL)")
plt.xlabel("Doanh thu (VND)")
plt.ylabel("Tên sự kiện")

# Hiển thị giá trị doanh thu trên cột
for i, v in enumerate(df["doanh_thu"]):
    plt.text(v + 100, i, f"{v:,} VND", va="center")

plt.subplots_adjust(left=0.3)
plt.tight_layout()
plt.show()

# Đóng kết nối
conn.close()