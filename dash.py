import streamlit as st
import pandas as pd
import numpy as np
import yfinance as yf
import plotly.express as px
from datetime import datetime
from streamlit_extras.metric_cards import style_metric_cards
from streamlit_extras.grid import grid


import time
import base64
from auth_utils import login_user, signup_user, logout_user, reset_password

# Initialize session state
if 'authenticated' not in st.session_state:
    st.session_state['authenticated'] = False
if 'user' not in st.session_state:
    st.session_state['user'] = None
if 'auth_mode' not in st.session_state:
    st.session_state['auth_mode'] = 'Login' # or 'Signup'

def get_base64_of_bin_file(bin_file):
    with open(bin_file, 'rb') as f:
        data = f.read()
    return base64.b64encode(data).decode()

def set_bg_hack(main_bg):
    '''
    A function to unpack an image from root folder and set as bg.
    The bg will be static and won't take resolution of device into account.
    '''
    bin_str = get_base64_of_bin_file(main_bg)
    page_bg_img = '''
    <style>
    .stApp {
        background-image: url("data:image/jpg;base64,%s");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    /* Make the login container semi-transparent white for readability */
    [data-testid="stVerticalBlock"] > [style*="flex-direction: column;"] > [data-testid="stVerticalBlock"] {
        background-color: rgba(255, 255, 255, 0.85);
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    </style>
    ''' % bin_str
    st.markdown(page_bg_img, unsafe_allow_html=True)

def build_sidebar():
    st.image("img/logo.jpg")
    
    # Logout button
    if st.sidebar.button("Sair / Logout"):
        logout_user()
        st.session_state['authenticated'] = False
        st.session_state['user'] = None
        st.rerun()
        
    st.divider()
    
    ticker_list = pd.read_csv("tickers_ibra.csv", index_col=0)
    tickers = st.multiselect(label="Selecione as Empresas", options=ticker_list, placeholder='Códigos')
    tickers = [t+".SA" for t in tickers]
    start_date = st.date_input("De", format="DD/MM/YYYY", value=datetime(2023,1,2))
    end_date = st.date_input("Até", format="DD/MM/YYYY", value="today")

    if tickers:
        data = yf.download(tickers, start=start_date, end=end_date)
        
        # Handle different return formats from yfinance
        if len(tickers) == 1:
            # Single ticker returns a DataFrame without multi-level columns
            if isinstance(data.columns, pd.MultiIndex):
                prices = data["Close"]
            else:
                prices = data["Close"]
            # Ensure it's a DataFrame with proper column name
            if not isinstance(prices, pd.DataFrame):
                prices = prices.to_frame()
            prices.columns = [tickers[0].rstrip(".SA")]
        else:
            # Multiple tickers return multi-level columns
            prices = data["Close"]
                    
        prices.columns = prices.columns.str.rstrip(".SA")
        
        # Download IBOV data
        ibov_data = yf.download("^BVSP", start=start_date, end=end_date)
        prices['IBOV'] = ibov_data["Close"]
        
        return tickers, prices
    return None, None

def build_main(tickers, prices):
    weights = np.ones(len(tickers))/len(tickers)
    prices['portfolio'] = prices.drop("IBOV", axis=1) @ weights
    norm_prices = 100 * prices / prices.iloc[0]
    returns = prices.pct_change()[1:]
    vols = returns.std()*np.sqrt(252)
    rets = (norm_prices.iloc[-1] - 100) / 100

    mygrid = grid(5 ,5 ,5 ,5 ,5 , 5, vertical_align="top")
    for t in prices.columns:
        c = mygrid.container(border=True)
        c.subheader(t, divider="red")
        colA, colB, colC = c.columns(3)
        if t == "portfolio":
            colA.image("img/logos/pie-chart-dollar-svgrepo-com.svg", width=85)
        elif t == "IBOV":
            colA.image("img/logos/pie-chart-svgrepo-com.svg", width=85)
        else:
            # Check if local image exists, otherwise fallback or skip
            # Remove numbers from ticker for logo filename (e.g., BBDC4 -> BBDC)
            ticker_letters = ''.join([c for c in t if c.isalpha()])
            try:
                colA.image(f'img/logos/{ticker_letters}.png', width=85)
            except:
                colA.write(t)
        colB.metric(label="retorno", value=f"{rets[t]:.0%}")
        colC.metric(label="volatilidade", value=f"{vols[t]:.0%}")
        style_metric_cards(background_color='rgba(255,255,255,0)')


st.set_page_config(layout="wide", page_title="Stone Edger")

# Authentication Logic
if not st.session_state['authenticated']:
    # Apply background image only on login screen
    try:
        set_bg_hack('img/bg.jpg')
    except Exception as e:
        print(f"Error loading background: {e}")

    col1, col2, col3 = st.columns([1, 2, 1])
    with col2:
        # Create a container for the login form to apply styling
        with st.container():
            st.image("img/logo.jpg", width=200)
            st.title("Bem-vindo ao Stone Edger")
            
            auth_mode = st.radio("Escolha uma opção:", ["Login", "Criar Conta", "Recuperar Senha"], horizontal=True)
            
            if auth_mode == "Recuperar Senha":
                st.info("Digite seu email para receber um link de redefinição de senha.")
                email = st.text_input("Email")
                if st.button("Enviar Email de Recuperação", type="primary", use_container_width=True):
                    if email:
                        response = reset_password(email)
                        if "error" not in response:
                            st.success("Email de recuperação enviado! Verifique sua caixa de entrada.")
                        else:
                            st.error(f"Erro ao enviar email: {response['error']}")
                    else:
                        st.warning("Por favor, preencha o email.")
            
            else:
                email = st.text_input("Email")
                password = st.text_input("Senha", type="password")
                
                if auth_mode == "Login":
                    if st.button("Entrar", type="primary", use_container_width=True):
                        if email and password:
                            response = login_user(email, password)
                            if "error" not in response:
                                st.session_state['authenticated'] = True
                                st.session_state['user'] = response.user
                                st.success("Login realizado com sucesso!")
                                time.sleep(1)
                                st.rerun()
                            else:
                                st.error(f"Erro ao fazer login: {response['error']}")
                        else:
                            st.warning("Por favor, preencha email e senha.")
                            
                else: # Create Account
                    if st.button("Criar Conta", type="primary", use_container_width=True):
                        if email and password:
                            response = signup_user(email, password)
                            if "error" not in response:
                                st.success("Conta criada com sucesso! Por favor, verifique seu email ou faça login.")
                            else:
                                st.error(f"Erro ao criar conta: {response['error']}")
                        else:
                            st.warning("Por favor, preencha email e senha.")

else:
    # Main Dashboard Application
    with st.sidebar:
        tickers, prices = build_sidebar()

    st.title('Python para Investidores')
    if tickers:
        build_main(tickers, prices)