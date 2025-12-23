import fundamentus as fd
import pandas as pd
import requests
import io
import os
from bs4 import BeautifulSoup

stock_code = 'PETR4'
print(f"Stock code set to: {stock_code}")
df = fd.get_detalhes_raw(stock_code)
work_data = pd.concat(df)
work_data1 = work_data[[0, 1,]]
work_data1 = work_data1.dropna()  # remove as linhas com dados do tipo NaN
work_data1.columns = ['texto', 'numeros' ]    #Renomeia o nome das colunas
work_data1= work_data1.rename_axis('index').reset_index()   #cria um novo index para a tabela
work_data1 = work_data1.drop(labels=[18,22], axis=0)   # remove as linas com informação desnecessárias
#print(work_data1)
work_data2 = work_data[[2, 3,]]
work_data2.columns = ['texto', 'numeros' ]
work_data2= work_data2.rename_axis('index').reset_index()   #cria um novo index para a tabela
work_data2 = work_data2.drop(labels=[7,19,23], axis=0)   # remove as linas com informação desnecessárias
#print(work_data2)
work_data3 = work_data[[4, 5,]]
work_data3 = work_data3.dropna()
work_data3.columns = ['texto', 'numeros' ]
work_data3= work_data3.rename_axis('index').reset_index()   #cria um novo index para a tabela
work_data3 = work_data3.drop(labels=0, axis=0)   # remove as linas com informação desnecessárias
#print(work_data3)
resultado_concat = pd.concat([work_data1, work_data2, work_data3], ignore_index=True)
resultado_concat['texto'] = resultado_concat['texto'].str.replace('?', '', regex=False)
#print(resultado_concat)
#**************************************************************************************
work_data = resultado_concat.T
work_data = work_data.drop(labels=["index"], axis=0)   # remove as linhas com informação desnecessárias
print(work_data)
work_data.to_excel(r'saida_single.xlsx', index=False)