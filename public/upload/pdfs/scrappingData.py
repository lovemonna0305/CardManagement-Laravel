import pdfplumber

# Open the PDF file
with pdfplumber.open('forpdf.pdf') as pdf:
    # Get the number of pages in the PDF
    num_pages = len(pdf.pages)

    data_array = []

    # Loop through each page and extract the text
    for page_num in range(num_pages):
        page = pdf.pages[page_num]
        data = page.extract_text()
        if page_num == 1:
            for line in data.strip().split("\n"):
                if "Data Hora Uso Carga Tarifa Tipo de Tarifa Operadora Nº Série CCIT Linha Valor Utiliz. (UT)" in line:
                    continue
                if line.strip():
                    parts = line.split()
                    data_array.append(" ".join(parts))

                for row in data_array:
                    print(row)
        
        # print(f'Page {page_num + 1}: {text}')

        

        