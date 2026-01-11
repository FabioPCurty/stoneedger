-- Create articles table
CREATE TABLE IF NOT EXISTS articles (
    id SERIAL PRIMARY KEY,
    titulo TEXT NOT NULL,
    categoria TEXT NOT NULL,
    tempo_leitura INTEGER NOT NULL,
    imagem_url TEXT NOT NULL,
    descricao TEXT NOT NULL,
    conteudo TEXT NOT NULL,
    autor TEXT NOT NULL,
    cargo_autor TEXT NOT NULL,
    avatar_autor TEXT NOT NULL,
    data_publicacao DATE NOT NULL,
    destaque BOOLEAN DEFAULT false,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()) NOT NULL
);

-- Enable RLS
ALTER TABLE articles ENABLE ROW LEVEL SECURITY;

-- Allow public to read articles
CREATE POLICY "Allow public read access" ON articles
    FOR SELECT USING (true);

-- Allow authenticated admins to manage articles
-- Assuming we use service role or a specific admin policy.
-- For simplicity in this dev phase, let's allow all actions if using service role (which bypasses RLS).
