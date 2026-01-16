-- FIX: Change result columns to BIGINT to avoid overflow for FIIs
ALTER TABLE stock_fundamentals 
ALTER COLUMN rendimento_12m TYPE BIGINT,
ALTER COLUMN rendimento_3m TYPE BIGINT;
