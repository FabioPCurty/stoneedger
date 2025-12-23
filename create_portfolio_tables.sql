-- Create tables for Portfolio Management
-- Compatible with PostgreSQL / Supabase

-- 1. Portfolios Table
CREATE TABLE IF NOT EXISTS portfolios (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID REFERENCES auth.users(id) NOT NULL,
    name TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT now() NOT NULL
);

-- Enable Row Level Security (RLS)
ALTER TABLE portfolios ENABLE ROW LEVEL SECURITY;

-- Create Policies for Portfolios
-- Allow users to view their own portfolios
CREATE POLICY "Users can view their own portfolios" 
ON portfolios FOR SELECT 
USING (auth.uid() = user_id);

-- Allow users to create their own portfolios
CREATE POLICY "Users can create their own portfolios" 
ON portfolios FOR INSERT 
WITH CHECK (auth.uid() = user_id);

-- Allow users to update their own portfolios
CREATE POLICY "Users can update their own portfolios" 
ON portfolios FOR UPDATE 
USING (auth.uid() = user_id);

-- Allow users to delete their own portfolios
CREATE POLICY "Users can delete their own portfolios" 
ON portfolios FOR DELETE 
USING (auth.uid() = user_id);


-- 2. User Assets Table
CREATE TABLE IF NOT EXISTS user_assets (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    portfolio_id UUID REFERENCES portfolios(id) ON DELETE CASCADE NOT NULL,
    ticker TEXT NOT NULL,
    quantity NUMERIC NOT NULL,
    avg_price NUMERIC NOT NULL,
    purchase_date DATE DEFAULT CURRENT_DATE
);

-- Enable Row Level Security (RLS)
ALTER TABLE user_assets ENABLE ROW LEVEL SECURITY;

-- Create Policies for User Assets
-- We check if the portfolio belongs to the user
CREATE POLICY "Users can view assets of their portfolios" 
ON user_assets FOR SELECT 
USING (
    EXISTS (
        SELECT 1 FROM portfolios 
        WHERE portfolios.id = user_assets.portfolio_id 
        AND portfolios.user_id = auth.uid()
    )
);

CREATE POLICY "Users can insert assets to their portfolios" 
ON user_assets FOR INSERT 
WITH CHECK (
    EXISTS (
        SELECT 1 FROM portfolios 
        WHERE portfolios.id = user_assets.portfolio_id 
        AND portfolios.user_id = auth.uid()
    )
);

CREATE POLICY "Users can update assets of their portfolios" 
ON user_assets FOR UPDATE 
USING (
    EXISTS (
        SELECT 1 FROM portfolios 
        WHERE portfolios.id = user_assets.portfolio_id 
        AND portfolios.user_id = auth.uid()
    )
);

CREATE POLICY "Users can delete assets of their portfolios" 
ON user_assets FOR DELETE 
USING (
    EXISTS (
        SELECT 1 FROM portfolios 
        WHERE portfolios.id = user_assets.portfolio_id 
        AND portfolios.user_id = auth.uid()
    )
);

-- Indexes for performance
CREATE INDEX IF NOT EXISTS idx_portfolios_user_id ON portfolios(user_id);
CREATE INDEX IF NOT EXISTS idx_user_assets_portfolio_id ON user_assets(portfolio_id);

COMMENT ON TABLE portfolios IS 'Stores user portfolios linked to auth.users';
COMMENT ON TABLE user_assets IS 'Stores assets belonging to a specific portfolio';
