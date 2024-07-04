import pdfplumber
import json
import re
import sys

pdf_file_path = './upload/pdfs/forpdf.pdf'
extracted_data =[]
# Open the PDF file
with pdfplumber.open(pdf_file_path) as pdf:
    # Get the number of pages in the PDF
    num_pages = len(pdf.pages)
    # Loop through each page and extract the text
    for page_num in range(num_pages):
        page = pdf.pages[page_num]
        text = page.extract_text()
        lines = text.split('\n')
        for line in lines:
            # print('lines', lines)
            columns = line.strip().split()
            if 'Cartão' in columns and 'VALE' in columns:
                cartao_index = columns.index('Cartão')
                if cartao_index + 1 < len(columns):
                    card_number = columns[cartao_index + 1]
            if len(columns) == 10 and columns[1].count(':') == 2 and columns[5] == 'VT':
                row_data = {
                    "Working_Days": columns[0],
                    "Working_Hour": columns[1],
                    # "Carga": columns[2],
                    # "Tarifa": columns[3],
                    # "Tipo": columns[4],
                    # "de Tarifa": columns[5],
                    # "Operadora": columns[6],
                    # "Nº Série CCIT": columns[7],
                    "Bus_Line": columns[8],
                    # "Valor Utiliz.": columns[9]
                }
                extracted_data.append(row_data)
                # print('extracted_data', extracted_data)

# Print the extracted data for debugging
# print(json.dumps(extracted_data))
# for data in extracted_data:
#     print(data)

final_output = {
    "card_number": card_number,
    "datas": extracted_data
}
print('final_output', json.dumps(final_output))
                
                
