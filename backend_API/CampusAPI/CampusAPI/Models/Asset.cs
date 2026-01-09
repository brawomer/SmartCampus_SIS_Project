using System.ComponentModel.DataAnnotations;

public class Asset
{
    [Key]
    public int AssetId { get; set; }
    public string AssetName { get; set; }

    // Change "AssetTag" to "QRCodeUID" to match SQL exactly
    public string QRCodeUID { get; set; }

    public int RoomId { get; set; }
    public string Status { get; set; }
}