-- Add FII columns to stock_fundamentals table
ALTER TABLE stock_fundamentals 
ADD COLUMN IF NOT EXISTS categoria VARCHAR(10) DEFAULT 'stocks',
ADD COLUMN IF NOT EXISTS mandato VARCHAR(100),
ADD COLUMN IF NOT EXISTS segmento VARCHAR(100),
ADD COLUMN IF NOT EXISTS gestao VARCHAR(100),
ADD COLUMN IF NOT EXISTS ffo_yield DECIMAL(10, 4),
ADD COLUMN IF NOT EXISTS ffo_cota DECIMAL(10, 2),
ADD COLUMN IF NOT EXISTS vp_cota DECIMAL(10, 2),
ADD COLUMN IF NOT EXISTS dividendo_cota DECIMAL(10, 2),
ADD COLUMN IF NOT EXISTS rendimento_12m DECIMAL(10, 4),
ADD COLUMN IF NOT EXISTS rendimento_3m DECIMAL(10, 4);

-- Update existing records to 'stocks'
UPDATE stock_fundamentals SET categoria = 'stocks' WHERE categoria IS NULL;
