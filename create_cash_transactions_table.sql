-- Create table for Cash Transactions / Cash Flow
CREATE TABLE IF NOT EXISTS cash_transactions (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    portfolio_id UUID REFERENCES portfolios(id) ON DELETE CASCADE NOT NULL,
    type TEXT NOT NULL, -- 'deposit', 'withdrawal', 'buy', 'sell', 'dividend', 'jcp', 'rent'
    amount NUMERIC NOT NULL,
    description TEXT,
    transaction_date DATE DEFAULT CURRENT_DATE NOT NULL,
    created_at TIMESTAMPTZ DEFAULT now() NOT NULL
);

-- Enable Row Level Security (RLS)
ALTER TABLE cash_transactions ENABLE ROW LEVEL SECURITY;

-- Create Policies for Cash Transactions
-- Users can view cash transactions of their portfolios
CREATE POLICY "Users can view cash transactions of their portfolios" 
ON cash_transactions FOR SELECT 
USING (
    EXISTS (
        SELECT 1 FROM portfolios 
        WHERE portfolios.id = cash_transactions.portfolio_id 
        AND portfolios.user_id = auth.uid()
    )
);

-- Users can insert cash transactions to their portfolios
CREATE POLICY "Users can insert cash transactions to their portfolios" 
ON cash_transactions FOR INSERT 
WITH CHECK (
    EXISTS (
        SELECT 1 FROM portfolios 
        WHERE portfolios.id = cash_transactions.portfolio_id 
        AND portfolios.user_id = auth.uid()
    )
);

-- Users can update cash transactions of their portfolios
CREATE POLICY "Users can update cash transactions of their portfolios" 
ON cash_transactions FOR UPDATE 
USING (
    EXISTS (
        SELECT 1 FROM portfolios 
        WHERE portfolios.id = cash_transactions.portfolio_id 
        AND portfolios.user_id = auth.uid()
    )
);

-- Users can delete cash transactions of their portfolios
CREATE POLICY "Users can delete cash transactions of their portfolios" 
ON cash_transactions FOR DELETE 
USING (
    EXISTS (
        SELECT 1 FROM portfolios 
        WHERE portfolios.id = cash_transactions.portfolio_id 
        AND portfolios.user_id = auth.uid()
    )
);

-- Index for performance
CREATE INDEX IF NOT EXISTS idx_cash_transactions_portfolio_id ON cash_transactions(portfolio_id);

COMMENT ON TABLE cash_transactions IS 'Stores cash flow transactions (deposits, withdrawals, buys, sells, yields)';
