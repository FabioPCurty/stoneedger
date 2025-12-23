import pandas as pd

# Read the Excel file
df = pd.read_excel('saida_single.xlsx', nrows=2)

# Get column names (first row)
columns = df.columns.tolist()

# Get data types from second row
data_types_row = df.iloc[1] if len(df) > 1 else df.iloc[0]

print("=" * 80)
print("COLUMN ANALYSIS")
print("=" * 80)
print(f"\nTotal columns: {len(columns)}\n")

for i, col in enumerate(columns):
    value = df.iloc[0, i] if len(df) > 0 else None
    type_hint = df.iloc[1, i] if len(df) > 1 else None
    
    print(f"{i+1}. Column: '{col}'")
    print(f"   Sample value: {value}")
    print(f"   Type hint: {type_hint}")
    print(f"   Pandas dtype: {df[col].dtype}")
    print()

# Save to a more readable format
output_df = pd.DataFrame({
    'Column Name': columns,
    'Sample Value': [df.iloc[0, i] if len(df) > 0 else None for i in range(len(columns))],
    'Type Hint': [df.iloc[1, i] if len(df) > 1 else None for i in range(len(columns))],
    'Pandas Type': [df[col].dtype for col in columns]
})

output_df.to_csv('column_analysis.csv', index=False)
print("\nColumn analysis saved to 'column_analysis.csv'")
